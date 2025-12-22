<?php

namespace App\Services\Monitoring;

use GuzzleHttp\Client;

class LibreNmsClient
{
    private Client $http;

    public function __construct()
    {
        $base = rtrim((string) config("opsai.librenms.base_url"), "/") . "/";

        $this->http = new Client([
            "base_uri" => $base,
            "timeout" => 20,
            "headers" => [
                "X-Auth-Token" => (string) config("opsai.librenms.token"),
                "Accept" => "application/json",
            ],
        ]);
    }

    /**
     * Alerts az elmúlt ablakban (windowSeconds). LibreNMS API eltérhet verziótól.
     * @return array<int,array<string,mixed>>
     */
    public function fetchRecentAlerts(int $windowSeconds): array
    {
        // Tipikus: /api/v0/alerts?state=1
        $res = $this->http->get("api/v0/alerts", [
            "query" => ["state" => 1],
        ]);

        $data = json_decode((string) $res->getBody(), true);
        $alerts = $data["alerts"] ?? [];
        if (!is_array($alerts)) {
            return [];
        }

        $cut = now()->subSeconds($windowSeconds);

        $out = [];
        foreach ($alerts as $a) {
            if (!is_array($a)) {
                continue;
            }

            // LibreNMS-ben sokszor timestamp "timestamp" / "time" / "last_changed"
            $ts = $a["timestamp"] ?? ($a["last_changed"] ?? null);
            $happenedAt = $ts ? \Carbon\CarbonImmutable::parse($ts) : null;
            if ($happenedAt && $happenedAt->lt($cut)) {
                continue;
            }

            $out[] = [
                "source" => "librenms",
                "external_id" =>
                    (string) ($a["id"] ?? ($a["alert_id"] ?? "alert")),
                "entity" =>
                    (string) ($a["hostname"] ?? ($a["device_hostname"] ?? "")),
                "check" => (string) ($a["rule"] ?? ($a["name"] ?? "alert")),
                "state_from" => (string) ($a["state_from"] ?? ""),
                "state_to" => (string) ($a["state_to"] ?? "ALERT"),
                "happened_at" => $happenedAt?->toDateTimeString(),
                "payload" => $a,
            ];
        }

        return $out;
    }

    /**
     * Tipikus “hiba kontextus”: device/ports/health
     */
    public function fetchDeviceContext(string $hostname): array
    {
        // verziófüggő, de tipikusan:
        // /api/v0/devices?hostname=
        $device = $this->http->get("api/v0/devices", [
            "query" => ["hostname" => $hostname],
        ]);
        $deviceData = json_decode((string) $device->getBody(), true);

        // Ports errors, etc:
        $ports = $this->http->get("api/v0/ports", [
            "query" => ["hostname" => $hostname],
        ]);
        $portsData = json_decode((string) $ports->getBody(), true);

        // Health metrics:
        $health = $this->http->get("api/v0/health", [
            "query" => ["hostname" => $hostname],
        ]);
        $healthData = json_decode((string) $health->getBody(), true);

        return [
            "device" => $deviceData,
            "ports" => $portsData,
            "health" => $healthData,
        ];
    }
}
