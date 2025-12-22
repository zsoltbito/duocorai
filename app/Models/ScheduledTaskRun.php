<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledTaskRun extends Model
{
    protected $fillable = [
        'scheduled_task_id',
        'started_at',
        'finished_at',
        'status',
        'output',
        'error',
        'duration_ms',
        'progress',
        'heartbeat_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'progress' => 'array',
        'heartbeat_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(ScheduledTask::class, 'scheduled_task_id');
    }
}
