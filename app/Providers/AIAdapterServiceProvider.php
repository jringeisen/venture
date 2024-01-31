<?php

namespace App\Providers;

use App\Services\Adapters\AI\OpenAI\OpenAIAdapter;
use Illuminate\Support\ServiceProvider;

class AIAdapterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenAIAdapter::class);
    }
}
