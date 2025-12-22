<?php

namespace App\Services\Learning;

use App\Models\DecisionLog;
use App\Models\KnowledgeFact;
use App\Models\KnowledgePattern;

class KnowledgeStore
{
    public function storeFact(
        string $source,
        string $signal,
        ?string $entityRef,
        array $context,
        int $weight = 1,
    ): void {
        KnowledgeFact::create([
            "source" => $source,
            "signal" => $signal,
            "entity_ref" => $entityRef,
            "context" => $context,
            "weight" => $weight,
            "observed_at" => now(),
        ]);
    }

    public function touchPattern(
        string $signature,
        string $title,
        ?string $solution = null,
        bool $success = false,
    ): KnowledgePattern {
        $p = KnowledgePattern::firstOrCreate(
            ["signature" => $signature],
            [
                "title" => $title,
                "solution" => $solution,
                "confidence" => 50,
                "hits" => 0,
                "success_hits" => 0,
            ],
        );

        $p->hits++;
        if ($success) {
            $p->success_hits++;
        }
        $p->confidence = (int) max(
            10,
            min(
                95,
                50 + $p->success_hits * 5 - ($p->hits - $p->success_hits) * 3,
            ),
        );
        if ($solution && !$p->solution) {
            $p->solution = $solution;
        }
        $p->save();

        return $p;
    }

    public function logDecision(
        string $source,
        ?string $subject,
        ?int $confidence,
        ?string $chosenProject,
        array $scores,
        array $explanation,
    ): void {
        DecisionLog::create([
            "source" => $source,
            "subject" => $subject,
            "confidence" => $confidence,
            "chosen_project" => $chosenProject,
            "scores" => $scores,
            "explanation" => $explanation,
        ]);
    }
}
