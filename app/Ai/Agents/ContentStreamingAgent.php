<?php

namespace App\Ai\Agents;

use App\Ai\Middleware\TrackTokenUsage;
use App\Models\PromptQuestion;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasMiddleware;
use Laravel\Ai\Promptable;
use Stringable;

#[Model('gpt-4o-mini')]
#[MaxTokens(10000)]
#[Timeout(300)]
class ContentStreamingAgent implements Agent, HasMiddleware
{
    use Promptable;

    public function __construct(
        public string $systemPrompt,
        public ?PromptQuestion $question = null
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return $this->systemPrompt.' Do not use emojis in your response.';
    }

    /**
     * Get the agent's middleware.
     */
    public function middleware(): array
    {
        return [
            new TrackTokenUsage($this->question),
        ];
    }
}
