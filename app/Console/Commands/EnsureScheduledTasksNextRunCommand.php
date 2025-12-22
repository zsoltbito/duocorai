<?php

namespace App\Console\Commands;

use App\Models\ScheduledTask;
use App\Services\Scheduler\NextRunCalculator;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class EnsureScheduledTasksNextRunCommand extends Command
{
    protected $signature = "schedule:ensure-next-run";
    protected $description = "Backfill next_run_at for enabled tasks";

    public function handle(NextRunCalculator $calc): int
    {
        $tasks = ScheduledTask::where("is_enabled", true)->get();

        foreach ($tasks as $task) {
            if (!$task->next_run_at) {
                $task->next_run_at = $calc->computeNextRun(
                    $task,
                    CarbonImmutable::now(),
                );
                $task->save();
            }
        }

        $this->info("ok");
        return self::SUCCESS;
    }
}
