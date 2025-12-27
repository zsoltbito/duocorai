<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiAnalysis extends Model
{
    protected $fillable = [
        "inbound_email_id",
        "title",
        "summary",
        "intent",
        "confidence",
        "missing_info",
        "raw_response",
    ];

    protected $casts = [
        "missing_info" => "array",
        "raw_response" => "array",
    ];
}
