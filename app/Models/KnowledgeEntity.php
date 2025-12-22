<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeEntity extends Model
{
    protected $fillable = ["type", "key", "label", "meta"];
    protected $casts = ["meta" => "array"];
}
