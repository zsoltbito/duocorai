<?php

namespace Database\Seeders;

use App\Models\ScheduledTask;
use App\Services\Scheduler\NextRunCalculator;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class AiAnalyzeScheduledTaskSeeder extends Seeder
{
    public function run(NextRunCalculator $calc): void
    {
        $task = ScheduledTask::updateOrCreate(
            ["task_key" => "ai.analyze"],
            [
                "name" => "AI analyze inbound emails",
                "handler" => "artisan",
                "command" => "ai:analyze-emails",
                "schedule_type" => "everyMinute",
                "timeout_seconds" => 300,
                "overlap_lock_seconds" => 300,
                "is_enabled" => true,
            ],
        );

        $task->next_run_at = $calc->computeNextRun(
            $task,
            CarbonImmutable::now(),
        );
        $task->save();
    }
}
