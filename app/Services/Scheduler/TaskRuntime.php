<?php

namespace App\Services\Scheduler;

use App\Models\ScheduledTaskRun;
use Carbon\CarbonImmutable;

class TaskRuntime
{
    public function __construct(private ScheduledTaskRun $run) {}

    public static function fromRunId(?int $runId): ?self
    {
        if (!$runId) {
            return null;
        }

        $run = ScheduledTaskRun::find($runId);
        return $run ? new self($run) : null;
    }

    public function runId(): int
    {
        return $this->run->id;
    }

    public function step(string $message, array $context = []): void
    {
        $progress = $this->run->progress ?? [];
        $progress[] = [
            "time" => CarbonImmutable::now()->toDateTimeString(),
            "message" => $message,
            "context" => $context,
        ];

        $this->run->update([
            "progress" => $progress,
            "heartbeat_at" => CarbonImmutable::now(),
        ]);
    }

    public function heartbeat(): void
    {
        $this->run->update([
            "heartbeat_at" => CarbonImmutable::now(),
        ]);
    }
}
