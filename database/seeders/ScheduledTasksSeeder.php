<?php

namespace Database\Seeders;

use App\Models\ScheduledTask;
use App\Services\Scheduler\NextRunCalculator;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class ScheduledTasksSeeder extends Seeder
{
    public function run(NextRunCalculator $calc): void
    {
        // Demo task: "inspire" (Laravel builtin)
        $task = ScheduledTask::updateOrCreate(
            ["task_key" => "demo.inspire"],
            [
                "name" => "Demo: inspire",
                "is_enabled" => true,
                "handler" => "artisan",
                "command" => "inspire",
                "schedule_type" => "everyMinute",
                "payload" => [],
                "timeout_seconds" => 30,
                "overlap_lock_seconds" => 60,
            ],
        );

        $task->next_run_at = $calc->computeNextRun(
            $task,
            CarbonImmutable::now(),
        );
        $task->save();
    }
}
