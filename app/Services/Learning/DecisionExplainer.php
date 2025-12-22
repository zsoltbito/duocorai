<?php

namespace App\Services\Learning;

class DecisionExplainer
{
    public function explainMonitoring(
        array $event,
        ?array $pattern = null,
    ): array {
        return [
            "summary" => "Monitoring state change ingested",
            "entity" => $event["entity"] ?? null,
            "check" => $event["check"] ?? null,
            "state_to" => $event["state_to"] ?? null,
            "pattern" => $pattern
                ? [
                    "signature" => $pattern["signature"] ?? null,
                    "confidence" => $pattern["confidence"] ?? null,
                    "solution" => $pattern["solution"] ?? null,
                ]
                : null,
        ];
    }
}
