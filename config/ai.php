<?php

return [
    "ollama" => [
        "url" => env("OLLAMA_URL", "http://localhost:11434/api/generate"),
        "model" => env("OLLAMA_MODEL", "llama3"),
    ],
];
