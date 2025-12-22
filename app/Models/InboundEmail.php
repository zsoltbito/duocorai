<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundEmail extends Model
{
    protected $fillable = [
        "message_id",
        "from_email",
        "from_name",
        "subject",
        "body_text",
        "body_html",
        "received_at",
        "imap_uid",
        "imap_folder",
        "processed",
    ];

    protected $casts = [
        "received_at" => "datetime",
        "processed" => "bool",
    ];
}
