<?php

namespace App\Services\Redmine;

use Illuminate\Support\Facades\Http;

class RedmineClient
{
    public function createIssue(
        string $project,
        string $title,
        string $description
    ): array {
        $res = Http::withHeaders([
            'X-Redmine-API-Key' => config('redmine.api_key'),
        ])->post(
            rtrim(config('redmine.url'), '/') . '/issues.json',
            [
                'issue' => [
                    'project_id' => $project,
                    'subject' => $title,
                    'description' => $description,
                ],
            ]
        );

        if (!$res->successful()) {
            throw new \RuntimeException('Redmine issue create failed');
        }

        return $res->json('issue');
    }
}
