<?php

namespace App\Http\Controllers;

use App\Models\ScheduledTask;
use App\Models\ScheduledTaskRun;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

class OpsDashboardController extends Controller
{
    public function index()
    {
        return view("ops.dashboard");
    }

    public function snapshot(): JsonResponse
    {
        $now = CarbonImmutable::now();
        $staleSeconds = 60;

        $running = ScheduledTaskRun::with("task:id,name,task_key")
            ->where("status", "running")
            ->orderByDesc("started_at")
            ->limit(20)
            ->get()
            ->map(function ($run) use ($now, $staleSeconds) {
                $hb = $run->heartbeat_at;
                $isStale = $hb
                    ? $hb->diffInSeconds($now) > $staleSeconds
                    : true;

                return [
                    "id" => $run->id,
                    "task" => [
                        "name" => $run->task?->name,
                        "task_key" => $run->task?->task_key,
                    ],
                    "started_at" => optional(
                        $run->started_at,
                    )->toDateTimeString(),
                    "heartbeat_at" => optional(
                        $run->heartbeat_at,
                    )->toDateTimeString(),
                    "is_stale" => $isStale,
                    "progress" => $run->progress ?? [],
                ];
            });

        $recentRuns = ScheduledTaskRun::with("task:id,name,task_key")
            ->orderByDesc("started_at")
            ->limit(30)
            ->get()
            ->map(function ($run) {
                return [
                    "id" => $run->id,
                    "task" => [
                        "name" => $run->task?->name,
                        "task_key" => $run->task?->task_key,
                    ],
                    "status" => $run->status,
                    "started_at" => optional(
                        $run->started_at,
                    )->toDateTimeString(),
                    "finished_at" => optional(
                        $run->finished_at,
                    )->toDateTimeString(),
                    "duration_ms" => $run->duration_ms,
                    "error" => $run->error,
                ];
            });

        $errors = ScheduledTaskRun::with("task:id,name,task_key")
            ->whereNotNull("error")
            ->orderByDesc("started_at")
            ->limit(20)
            ->get()
            ->map(function ($run) {
                return [
                    "id" => $run->id,
                    "task" => [
                        "name" => $run->task?->name,
                        "task_key" => $run->task?->task_key,
                    ],
                    "status" => $run->status,
                    "started_at" => optional(
                        $run->started_at,
                    )->toDateTimeString(),
                    "error" => $run->error,
                ];
            });

        $tasks = ScheduledTask::orderByDesc("is_enabled")
            ->orderBy("next_run_at")
            ->limit(50)
            ->get()
            ->map(function ($t) {
                return [
                    "id" => $t->id,
                    "name" => $t->name,
                    "task_key" => $t->task_key,
                    "is_enabled" => (bool) $t->is_enabled,
                    "handler" => $t->handler,
                    "command" => $t->command,
                    "schedule_type" => $t->schedule_type,
                    "last_status" => $t->last_status,
                    "last_run_at" => optional(
                        $t->last_run_at,
                    )->toDateTimeString(),
                    "next_run_at" => optional(
                        $t->next_run_at,
                    )->toDateTimeString(),
                    "last_error" => $t->last_error,
                ];
            });

        return response()->json([
            "now" => $now->toDateTimeString(),
            "running" => $running,
            "recent_runs" => $recentRuns,
            "errors" => $errors,
            "tasks" => $tasks,
        ]);
    }
}
