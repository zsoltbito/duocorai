<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DecisionLog extends Model
{
    protected $fillable = [
        "source",
        "subject",
        "confidence",
        "chosen_project",
        "scores",
        "explanation",
    ];
    protected $casts = ["scores" => "array", "explanation" => "array"];
}
