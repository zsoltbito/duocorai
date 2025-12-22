<?php

return [
    "default" => "default",

    "accounts" => [
        "default" => [
            "host" => env("IMAP_HOST"),
            "port" => env("IMAP_PORT", 993),
            "protocol" => "imap",
            "encryption" => env("IMAP_ENCRYPTION", "ssl"),
            "validate_cert" => filter_var(
                env("IMAP_VALIDATE_CERT", true),
                FILTER_VALIDATE_BOOL,
            ),
            "username" => env("IMAP_USERNAME"),
            "password" => env("IMAP_PASSWORD"),
            "authentication" => null,
            "timeout" => 30,
        ],
    ],
];
