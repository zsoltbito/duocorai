<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgePattern extends Model
{
    protected $fillable = [
        "signature",
        "title",
        "solution",
        "confidence",
        "hits",
        "success_hits",
        "meta",
    ];
    protected $casts = ["meta" => "array"];
}
