<?php

namespace App\Http\Controllers;

use App\Models\ScheduledTask;
use App\Services\Scheduler\NextRunCalculator;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SchedulerController extends Controller
{
    public function index()
    {
        return view("scheduler.index", [
            "tasks" => ScheduledTask::orderBy("name")->get(),
        ]);
    }

    public function create()
    {
        return view("scheduler.form", [
            "task" => new ScheduledTask(),
            "mode" => "create",
        ]);
    }

    public function edit(ScheduledTask $task)
    {
        return view("scheduler.form", [
            "task" => $task,
            "mode" => "edit",
        ]);
    }

    public function store(Request $request, NextRunCalculator $calc)
    {
        $data = $this->validateData($request);

        $task = ScheduledTask::create($data);
        $task->next_run_at = $calc->computeNextRun(
            $task,
            CarbonImmutable::now(),
        );
        $task->save();

        return redirect()
            ->route("scheduler.index")
            ->with("ok", "Task létrehozva.");
    }

    public function update(
        Request $request,
        ScheduledTask $task,
        NextRunCalculator $calc,
    ) {
        $data = $this->validateData($request);

        $task->update($data);
        $task->next_run_at = $calc->computeNextRun(
            $task,
            CarbonImmutable::now(),
        );
        $task->save();

        return redirect()
            ->route("scheduler.index")
            ->with("ok", "Task frissítve.");
    }

    public function toggle(ScheduledTask $task)
    {
        $task->is_enabled = !$task->is_enabled;
        $task->save();

        return back();
    }

    public function runNow(ScheduledTask $task)
    {
        $task->next_run_at = CarbonImmutable::now();
        $task->save();

        Artisan::call("schedule:run-dynamic", ["--limit" => 1]);

        return back()->with("ok", "Task azonnali futtatása elindítva.");
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            "name" => "required|string|max:255",
            "task_key" => "required|string|max:255",
            "handler" => "required|in:artisan,job",
            "command" => "nullable|string",
            "job_class" => "nullable|string",
            "schedule_type" =>
                "required|in:everyMinute,everyFiveMinutes,hourly,daily",
            "timeout_seconds" => "required|integer|min:5|max:3600",
            "overlap_lock_seconds" => "required|integer|min:5|max:3600",
        ]);
    }
}
