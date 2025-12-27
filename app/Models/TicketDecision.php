<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TicketDecision extends Model
{
    protected $fillable = [
        "inbound_email_id",
        "ai_analysis_id",
        "created_ticket",
        "reason",
        "redmine_issue_id",
        "redmine_project",
    ];

    protected $casts = [
        "created_ticket" => "bool",
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function inboundEmail(): BelongsTo
    {
        return $this->belongsTo(InboundEmail::class);
    }

    public function aiAnalysis(): BelongsTo
    {
        return $this->belongsTo(AiAnalysis::class);
    }

    /**
     * One ticket decision may have one outbound reply email.
     */
    public function outboundEmail(): HasOne
    {
        return $this->hasOne(
            OutboundEmail::class,
            "inbound_email_id",
            "inbound_email_id",
        );
    }
}
