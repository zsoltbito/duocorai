<?php

namespace App\Services\Scheduler;

use App\Models\ScheduledTask;
use Carbon\CarbonImmutable;

class NextRunCalculator
{
    public function computeNextRun(
        ScheduledTask $task,
        ?CarbonImmutable $from = null,
    ): CarbonImmutable {
        $from = $from ?? CarbonImmutable::now();

        return match ($task->schedule_type) {
            "everyMinute" => $from->addMinute()->startOfMinute(),
            "everyFiveMinutes" => $from
                ->addMinutes(5 - ($from->minute % 5))
                ->startOfMinute(),
            "hourly" => $from->addHour()->startOfHour(),
            "daily" => $from->addDay()->startOfDay(),
            default => $from->addMinute()->startOfMinute(),
        };
    }
}
