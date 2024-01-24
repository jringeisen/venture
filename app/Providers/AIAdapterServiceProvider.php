<?php

namespace App\Providers;

use App\Services\Adapters\AI\OpenAI\OpenAIAdapter;
use App\Services\AI\OpenAIService;
use Illuminate\Http\Request;
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
