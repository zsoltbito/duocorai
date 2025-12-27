<?php

namespace App\Services\Ai;

use App\Models\InboundEmail;

class AiAnalyzer
{
    public function __construct(private readonly AiClientInterface $client) {}

    public function analyze(InboundEmail $email): array
    {
        $prompt = $this->buildPrompt($email);
        $data = $this->client->analyze($prompt);

        return [
            "title" => $data["title"] ?? null,
            "summary" => $data["summary"] ?? null,
            "intent" => $data["intent"] ?? null,
            "confidence" => (int) ($data["confidence"] ?? 0),
            "missing_info" => $data["missing_info"] ?? [],
            "raw_response" => $data,
        ];
    }

    private function buildPrompt(InboundEmail $email): string
    {
        return <<<PROMPT
        You are an IT helpdesk AI.

        Return ONLY valid JSON.
        No markdown. No explanation.

        Schema:
        {
          "title": string,
          "summary": string,
          "intent": string,
          "confidence": number (0-100),
          "missing_info": string[]
        }

        Rules:
        - Rewrite the problem in clear, professional language
        - If information is missing, list it
        - Low confidence if unclear or incomplete

        Email:
        From: {$email->from_email}
        Subject: {$email->subject}

        Body:
        {$email->body_text}
        PROMPT;
    }
}
