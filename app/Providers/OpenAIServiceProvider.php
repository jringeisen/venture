<?php

namespace App\Providers;

use App\Services\OpenAI\OpenAIChatService;
use App\Services\OpenAI\OpenAIModerationService;
use Illuminate\Support\ServiceProvider;

class OpenAIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenAIChatService::class);
        $this->app->singleton(OpenAIModerationService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
