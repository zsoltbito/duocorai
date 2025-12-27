<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketDecision extends Model
{
    protected $fillable = [
        'inbound_email_id',
        'ai_analysis_id',
        'created_ticket',
        'reason',
        'redmine_issue_id',
        'redmine_project',
    ];
}
