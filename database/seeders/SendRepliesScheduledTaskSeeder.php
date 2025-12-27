<?php

namespace Database\Seeders;

use App\Models\ScheduledTask;
use App\Services\Scheduler\NextRunCalculator;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class SendRepliesScheduledTaskSeeder extends Seeder
{
    public function run(NextRunCalculator $calc): void
    {
        $task = ScheduledTask::updateOrCreate(
            ["task_key" => "mail.send-replies"],
            [
                "name" => "Send AI reply emails",
                "handler" => "artisan",
                "command" => "mail:send-replies",
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
