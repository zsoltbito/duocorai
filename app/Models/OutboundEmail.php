<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundEmail extends Model
{
    protected $fillable = [
        "inbound_email_id",
        "to_email",
        "subject",
        "body_text",
        "type",
        "sent_at",
    ];

    protected $casts = [
        "sent_at" => "datetime",
    ];
}
