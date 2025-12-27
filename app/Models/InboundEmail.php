<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\AiAnalysis;

class InboundEmail extends Model
{
    protected $table = 'inbound_emails';

    protected $fillable = [
        'message_id',
        'from_email',
        'from_name',
        'subject',
        'body_text',
        'body_html',
        'received_at',
        'imap_uid',
        'imap_folder',
        'processed',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'processed'   => 'bool',
    ];

    /**
     * One inbound email has exactly one AI analysis.
     */
    public function aiAnalysis(): HasOne
    {
        return $this->hasOne(AiAnalysis::class, 'inbound_email_id');
    }
}
