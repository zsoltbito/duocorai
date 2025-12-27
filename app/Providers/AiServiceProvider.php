<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Ai\AiClientInterface;
use App\Services\Ai\OllamaClient;

class AiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AiClientInterface::class, function () {
            return new OllamaClient();
        });
    }
}
