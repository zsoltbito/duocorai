<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalTicketLink extends Model
{
    protected $fillable = ["source", "external_ref", "redmine_issue_id"];
}
