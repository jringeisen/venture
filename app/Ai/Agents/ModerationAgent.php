<?php

namespace App\Ai\Agents;

use App\Models\Prompt;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\UseCheapestModel;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

#[UseCheapestModel]
class ModerationAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $customPrompt = Prompt::where('category', 'moderation')->first()?->prompt ?? '';

        return <<<INSTRUCTIONS
You are a content moderation system. You must evaluate user-submitted content for safety and appropriateness.

## Safety Policy
Evaluate the content against these categories and flag if ANY are violated:
- Sexual content or references
- Hate speech, discrimination, or harassment
- Violence or threats
- Self-harm or dangerous activities
- Illegal activities
- Profanity or vulgar language
- Content inappropriate for children/students

## Custom Moderation Rules
{$customPrompt}

## Response Guidelines
- Set "flagged" to true if the content violates ANY safety policy or custom rule
- When flagged, provide a clear, child-friendly message explaining why the content was rejected
- When not flagged, set "message" to null
INSTRUCTIONS;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'flagged' => $schema->boolean()->required(),
            'message' => $schema->string()->nullable()->required(),
        ];
    }
}
