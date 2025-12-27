<?php

namespace App\Console\Commands;

use App\Models\InboundEmail;
use App\Services\Ticketing\TicketDecisionService;
use App\Services\Scheduler\TaskRuntime;
use Illuminate\Console\Command;
use Throwable;

class CreateTicketsCommand extends Command
{
    protected $signature = "tickets:create {--runId=}";
    protected $description = "Create tickets from AI-analyzed emails";

    public function handle(TicketDecisionService $service): int
    {
        $runtime = TaskRuntime::fromRunId($this->option("runId"));
        $runtime?->step("Ticket decision started");

        $this->info("[TICKETS] Ticket feldolgozás indult");

        $emails = InboundEmail::where("processed", false)
            ->whereHas("aiAnalysis")
            ->get();

        if ($emails->isEmpty()) {
            $this->line("[TICKETS] Nincs feldolgozható email");
            $runtime?->step("No emails to process");
            return self::SUCCESS;
        }

        $created = 0;
        $rejected = 0;

        foreach ($emails as $email) {
            try {
                $decision = $service->handle($email);

                if ($decision->created_ticket) {
                    $created++;
                } else {
                    $rejected++;
                }
            } catch (Throwable $e) {
                $this->error("[TICKETS] Hiba email feldolgozásakor");
                $this->error($e->getMessage());

                $runtime?->step("Ticket error", [
                    "email_id" => $email->id,
                    "error" => $e->getMessage(),
                ]);
            }
        }

        $this->info("[TICKETS] Feldolgozott emailek: {$emails->count()}");
        $this->info("[TICKETS] Létrehozott ticketek: {$created}");
        $this->info("[TICKETS] Elutasított: {$rejected}");

        $runtime?->step("Ticket decision finished", [
            "processed" => $emails->count(),
            "created" => $created,
            "rejected" => $rejected,
        ]);

        return self::SUCCESS;
    }
}
