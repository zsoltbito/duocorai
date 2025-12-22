<?php

namespace App\Services\Monitoring;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class NagiosClient
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            "timeout" => 20,
        ]);
    }

    /**
     * Vár egy JSON tömböt "events" kulccsal vagy sima listát.
     * Minden elem legalább:
     *  - external_id (pl host/service)
     *  - entity (host)
     *  - check (service)
     *  - state_from, state_to
     *  - happened_at (ISO)
     *
     * @return array<int,array<string,mixed>>
     */
    public function fetchStateChanges(int $windowSeconds): array
    {
        $url = config("opsai.nagios.events_url");
        if (!$url) {
            return [];
        }

        $res = $this->http->get($url, [
            "auth" => config("opsai.nagios.user")
                ? [config("opsai.nagios.user"), config("opsai.nagios.pass")]
                : null,
            "headers" => ["Accept" => "application/json"],
        ]);

        $data = json_decode((string) $res->getBody(), true);
        if (!$data) {
            return [];
        }

        $events = $data["events"] ?? $data;
        if (!is_array($events)) {
            return [];
        }

        $cut = now()->subSeconds($windowSeconds);

        $out = [];
        foreach ($events as $e) {
            if (!is_array($e)) {
                continue;
            }

            $ts = $e["happened_at"] ?? null;
            $happenedAt = $ts ? \Carbon\CarbonImmutable::parse($ts) : null;
            if ($happenedAt && $happenedAt->lt($cut)) {
                continue;
            }

            $out[] = [
                "source" => "nagios",
                "external_id" => (string) ($e["external_id"] ?? Str::uuid()),
                "entity" => (string) ($e["entity"] ?? ""),
                "check" => (string) ($e["check"] ?? ""),
                "state_from" => (string) ($e["state_from"] ?? ""),
                "state_to" => (string) ($e["state_to"] ?? ""),
                "happened_at" => $happenedAt?->toDateTimeString(),
                "payload" => $e,
            ];
        }

        return $out;
    }
}
