<?php

namespace App\Services\Learning;

use App\Models\KnowledgePattern;

class PatternMatcher
{
    public function signatureFromEvent(
        string $entity,
        string $check,
        string $stateTo,
    ): string {
        $s = strtolower(trim($entity . "|" . $check . "|" . $stateTo));
        $s = preg_replace("/\s+/", " ", $s);
        $s = preg_replace("/[^a-z0-9\|\-_: ]+/", "", $s);
        return $s;
    }

    public function bestMatch(string $signature): ?KnowledgePattern
    {
        return KnowledgePattern::where("signature", $signature)->first();
    }
}
