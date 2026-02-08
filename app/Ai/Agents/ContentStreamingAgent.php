<?php

namespace App\Ai\Agents;

use App\Ai\Middleware\TrackTokenUsage;
use App\Models\PromptQuestion;
use Laravel\Ai\Attributes\UseCheapestModel;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasMiddleware;
use Laravel\Ai\Promptable;
use Stringable;

#[UseCheapestModel]
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
        return $this->systemPrompt;
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
