<?php

namespace App\Services\Redmine;

use GuzzleHttp\Client;

class RedmineClient
{
    private Client $http;

    public function __construct()
    {
        $base = rtrim((string) env("REDMINE_BASE_URL"), "/") . "/";

        $this->http = new Client([
            "base_uri" => $base,
            "timeout" => 20,
            "headers" => [
                "X-Redmine-API-Key" => (string) env("REDMINE_API_KEY"),
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
        ]);
    }

    public function createIssue(array $issue): int
    {
        $res = $this->http->post("issues.json", [
            "json" => ["issue" => $issue],
        ]);

        $data = json_decode((string) $res->getBody(), true);
        $id = (int) ($data["issue"]["id"] ?? 0);

        if ($id <= 0) {
            throw new \RuntimeException("Redmine did not return issue.id");
        }

        return $id;
    }
}
