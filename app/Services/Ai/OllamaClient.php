<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;

class OllamaClient implements AiClientInterface
{
    public function analyze(string $prompt): array
    {
        $res = Http::timeout(120)->post(config("ai.ollama.url"), [
            "model" => config("ai.ollama.model"),
            "prompt" => $prompt,
            "stream" => false,
        ]);

        $text = $res->json("response");

        if (!$text) {
            throw new \RuntimeException("Empty AI response");
        }

        // JSON extract (LLM néha szöveget is mellétesz)
        if (!preg_match("/\{.*\}/s", $text, $m)) {
            throw new \RuntimeException("AI did not return valid JSON");
        }

        return json_decode($m[0], true, 512, JSON_THROW_ON_ERROR);
    }
}
