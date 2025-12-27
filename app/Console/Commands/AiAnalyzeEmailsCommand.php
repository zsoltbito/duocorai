<?php

namespace App\Console\Commands;

use App\Models\InboundEmail;
use App\Models\AiAnalysis;
use App\Services\Ai\AiAnalyzer;
use App\Services\Scheduler\TaskRuntime;
use Illuminate\Console\Command;
use Throwable;

class AiAnalyzeEmailsCommand extends Command
{
    protected $signature = "ai:analyze-emails {--runId=}";
    protected $description = "Analyze inbound emails with AI";

    public function handle(AiAnalyzer $analyzer): int
    {
        $runtime = TaskRuntime::fromRunId($this->option("runId"));
        $runtime?->step("AI analysis started");

        $emails = InboundEmail::where("processed", false)
            ->whereDoesntHave("aiAnalysis")
            ->limit(10)
            ->get();

        $count = 0;

        foreach ($emails as $email) {
            try {
                $result = $analyzer->analyze($email);

                AiAnalysis::create([
                    "inbound_email_id" => $email->id,
                    ...$result,
                ]);

                $count++;
            } catch (Throwable $e) {
                $runtime?->step("AI error", [
                    "email_id" => $email->id,
                    "error" => $e->getMessage(),
                ]);
            }
        }

        $runtime?->step("AI analysis finished", ["analyzed" => $count]);

        return self::SUCCESS;
    }
}
