<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ScheduledTaskRun;

class ScheduledTask extends Model
{
    protected $fillable = [
        "name",
        "task_key",
        "is_enabled",
        "handler",
        "command",
        "job_class",
        "schedule_type",
        "payload",
        "timeout_seconds",
        "overlap_lock_seconds",
        "last_run_at",
        "next_run_at",
        "last_status",
        "last_error",
    ];

    protected $casts = [
        "is_enabled" => "bool",
        "payload" => "array",
        "last_run_at" => "datetime",
        "next_run_at" => "datetime",
    ];

    public function runs(): HasMany
    {
        return $this->hasMany(ScheduledTaskRun::class);
    }
}
