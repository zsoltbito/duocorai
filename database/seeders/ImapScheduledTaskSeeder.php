<?php

namespace Database\Seeders;

use App\Models\ScheduledTask;
use App\Services\Scheduler\NextRunCalculator;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class ImapScheduledTaskSeeder extends Seeder
{
    public function run(NextRunCalculator $calc): void
    {
        $task = ScheduledTask::updateOrCreate(
            ["task_key" => "imap.check"],
            [
                "name" => "IMAP inbox check",
                "handler" => "artisan",
                "command" => "imap:check",
                "schedule_type" => "everyMinute",
                "timeout_seconds" => 120,
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
