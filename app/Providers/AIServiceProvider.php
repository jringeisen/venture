<?php

namespace App\Providers;

use App\Services\Adapters\AI\OpenAI\OpenAIAdapter;
use App\Services\AI\OpenAIService;
use App\Services\Interfaces\AIServiceInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app
            ->singleton(
                OpenAIService::class,
                function (Application $app) {
                    $request = app(Request::class);

                    return new OpenAIService($app->make(OpenAIAdapter::class), $request->user());
                }
            );

        $this->app
            ->bind(
                AIServiceInterface::class,
                function (Application $app, array $context) {
                    throw_if(
                        !isset($context['aiService']),
                        "AI Service is required to new up an AIServiceInterface"
                    );

                    if ($context['aiService'] === 'OpenAI') {
                        return $app->make(OpenAIService::class);
                    }

                    throw new RuntimeException("Unknown aiService passed through: {$context['aiService']}");
                }
            );
    }
}
