<?php

namespace App\Console\Commands;

use App\Models\ScheduledTask;
use App\Models\ScheduledTaskRun;
use App\Services\Scheduler\NextRunCalculator;
use App\Services\Scheduler\TaskRuntime;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Throwable;

class RunDynamicScheduledTasksCommand extends Command
{
    protected $signature = "schedule:run-dynamic {--limit=50}";
    protected $description = "Run DB-driven scheduled tasks that are due";

    public function handle(NextRunCalculator $calc): int
    {
        $now = CarbonImmutable::now();
        $limit = (int) $this->option("limit");

        $tasks = ScheduledTask::query()
            ->where("is_enabled", true)
            ->whereNotNull("next_run_at")
            ->where("next_run_at", "<=", $now)
            ->orderBy("next_run_at")
            ->limit($limit)
            ->get();

        foreach ($tasks as $task) {
            $this->runOneTask($task, $calc);
        }

        return self::SUCCESS;
    }

    private function runOneTask(
        ScheduledTask $task,
        NextRunCalculator $calc,
    ): void {
        $lock = Cache::lock(
            "scheduled_task_lock:{$task->id}",
            (int) $task->overlap_lock_seconds,
        );

        if (!$lock->get()) {
            $task->update([
                "last_status" => "skipped",
                "last_error" => "Overlap lock active",
                "next_run_at" => $calc->computeNextRun($task),
            ]);
            return;
        }

        $run = null;

        try {
            $run = ScheduledTaskRun::create([
                "scheduled_task_id" => $task->id,
                "started_at" => CarbonImmutable::now(),
                "status" => "running",
                "progress" => [],
                "heartbeat_at" => CarbonImmutable::now(),
            ]);

            $runtime = new TaskRuntime($run);
            $runtime->step("Task started", [
                "task_key" => $task->task_key,
            ]);

            $t0 = microtime(true);

            [$status, $output] = $this->runHandler($task, $runtime);

            $durationMs = (int) round((microtime(true) - $t0) * 1000);

            $runtime->step("Task finished", [
                "status" => $status,
                "duration_ms" => $durationMs,
            ]);

            $run->update([
                "finished_at" => CarbonImmutable::now(),
                "status" => $status,
                "output" => $output,
                "duration_ms" => $durationMs,
            ]);

            $task->update([
                "last_run_at" => CarbonImmutable::now(),
                "last_status" => $status,
                "last_error" => null,
                "next_run_at" => $calc->computeNextRun($task),
            ]);
        } catch (Throwable $e) {
            if ($run) {
                $run->update([
                    "finished_at" => CarbonImmutable::now(),
                    "status" => "failed",
                    "error" => $e->getMessage(),
                ]);
            }

            $task->update([
                "last_run_at" => CarbonImmutable::now(),
                "last_status" => "failed",
                "last_error" => $e->getMessage(),
                "next_run_at" => $calc->computeNextRun($task),
            ]);
        } finally {
            $lock->release();
        }
    }

    /**
     * Execute the task handler (artisan or job)
     *
     * @return array{0:string,1:?string}
     */
    private function runHandler(
        ScheduledTask $task,
        TaskRuntime $runtime,
    ): array {
        $payload = $task->payload ?? [];

        if ($task->handler === "artisan") {
            $command = trim((string) $task->command);

            if ($command === "") {
                throw new \RuntimeException("Missing artisan command");
            }

            $args = is_array($payload) ? $payload : [];
            $args["--runId"] = $runtime->runId();

            $runtime->step("Running artisan command", [
                "command" => $command,
                "payload" => $args,
            ]);

            $exitCode = Artisan::call($command, $args);
            $output = Artisan::output();

            return [$exitCode === 0 ? "success" : "failed", $output ?: null];
        }

        if ($task->handler === "job") {
            $jobClass = trim((string) $task->job_class);

            if ($jobClass === "" || !class_exists($jobClass)) {
                throw new \RuntimeException("Invalid job_class");
            }

            $runtime->step("Dispatching job", [
                "job" => $jobClass,
                "payload" => $payload,
            ]);

            dispatch(new $jobClass($payload, $runtime->runId()));

            return ["success", "Job dispatched"];
        }

        throw new \RuntimeException("Unknown handler: " . $task->handler);
    }
}
