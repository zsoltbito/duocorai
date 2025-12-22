<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeFact extends Model
{
    protected $fillable = [
        "source",
        "signal",
        "entity_ref",
        "context",
        "weight",
        "observed_at",
    ];
    protected $casts = ["context" => "array", "observed_at" => "datetime"];
}
