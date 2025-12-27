<?php

namespace App\Console\Commands;

use App\Models\InboundEmail;
use App\Models\OutboundEmail;
use App\Models\TicketDecision;
use App\Services\Mail\ReplyBuilder;
use App\Services\Scheduler\TaskRuntime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReplyEmailsCommand extends Command
{
    protected $signature = "mail:send-replies {--runId=}";
    protected $description = "Send AI-generated reply emails";

    public function handle(ReplyBuilder $builder): int
    {
        $runtime = TaskRuntime::fromRunId($this->option("runId"));
        $runtime?->step("Reply email sending started");

        $decisions = TicketDecision::whereDoesntHave("outboundEmail")->get();

        if ($decisions->isEmpty()) {
            $this->line("[MAIL] Nincs küldendő válasz");
            return self::SUCCESS;
        }

        foreach ($decisions as $decision) {
            $email = $decision->inboundEmail;

            $reply = $builder->build($email, $decision);

            Mail::raw($reply["body"], function ($m) use ($email, $reply) {
                $m->to($email->from_email)->subject($reply["subject"]);
            });

            OutboundEmail::create([
                "inbound_email_id" => $email->id,
                "to_email" => $email->from_email,
                "subject" => $reply["subject"],
                "body_text" => $reply["body"],
                "type" => $reply["type"],
                "sent_at" => now(),
            ]);

            $email->markProcessed();
        }

        $runtime?->step("Reply email sending finished", [
            "sent" => $decisions->count(),
        ]);

        return self::SUCCESS;
    }
}
