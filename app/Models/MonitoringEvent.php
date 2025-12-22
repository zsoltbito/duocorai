<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringEvent extends Model
{
    protected $fillable = [
        "source",
        "external_id",
        "entity",
        "check",
        "state_from",
        "state_to",
        "happened_at",
        "fingerprint",
        "payload",
        "redmine_issue_id",
        "status",
        "error",
    ];

    protected $casts = [
        "payload" => "array",
        "happened_at" => "datetime",
    ];
}
