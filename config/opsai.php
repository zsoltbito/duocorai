<?php

return [
    "imap" => [
        "folder" => env("IMAP_FOLDER", "INBOX"),
    ],

    "ai" => [
        "base_url" => env("AI_BASE_URL", "http://127.0.0.1:11434"),
        "model" => env("AI_MODEL", "llama3.1:8b"),
        "timeout" => (int) env("AI_TIMEOUT", 60),
    ],

    "redmine" => [
        "base_url" => env("REDMINE_BASE_URL"),
        "api_key" => env("REDMINE_API_KEY"),
        "default_project" => env("REDMINE_DEFAULT_PROJECT_IDENTIFIER", "it"),
        "default_tracker_id" => (int) env("REDMINE_DEFAULT_TRACKER_ID", 1),
        "default_priority_id" => (int) env("REDMINE_DEFAULT_PRIORITY_ID", 2),
    ],

    "nagios" => [
        "events_url" => env("NAGIOS_EVENTS_URL"),
        "user" => env("NAGIOS_USER"),
        "pass" => env("NAGIOS_PASS"),
        "poll_window_seconds" => (int) env("NAGIOS_POLL_WINDOW_SECONDS", 600),
    ],

    "librenms" => [
        "base_url" => env("LIBRENMS_BASE_URL"),
        "token" => env("LIBRENMS_TOKEN"),
        "poll_window_seconds" => (int) env("LIBRENMS_POLL_WINDOW_SECONDS", 600),
    ],

    "exports" => [
        "disk" => env("EXPORT_DISK", "local"),
        "dir" => env("EXPORT_DIR", "exports"),
    ],
];
