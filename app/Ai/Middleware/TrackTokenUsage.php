<?php

namespace App\Ai\Middleware;

use App\Models\PromptQuestion;
use Closure;
use Laravel\Ai\Prompts\AgentPrompt;
use Laravel\Ai\Responses\AgentResponse;

class TrackTokenUsage
{
    public function __construct(public ?PromptQuestion $question = null) {}

    /**
     * Handle the incoming prompt.
     */
    public function handle(AgentPrompt $prompt, Closure $next): mixed
    {
        return $next($prompt)->then(function (AgentResponse $response) {
            if ($this->question && $response->usage) {
                $totalTokens = $response->usage->promptTokens + $response->usage->completionTokens;

                $this->question->update([
                    'total_tokens' => $this->question->total_tokens + $totalTokens,
                ]);
            }
        });
    }
}
