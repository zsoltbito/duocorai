<?php

namespace App\Services\Ai;

interface AiClientInterface
{
    /**
     * @return array Parsed JSON response
     */
    public function analyze(string $prompt): array;
}
