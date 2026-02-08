<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

#[Model('gpt-5-mini')]
#[MaxTokens(16000)]
#[Timeout(300)]
class SingleCourseWeekGenerationAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are an expert K-12 curriculum designer. Always respond with valid JSON only.';
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'week_number' => $schema->integer()->required(),
            'title' => $schema->string()->required(),
            'description' => $schema->string()->required(),
            'learning_objectives' => $schema->array()->items($schema->string())->required(),
            'days' => $schema->array()->items($schema->object([
                'day_number' => $schema->integer()->required(),
                'title' => $schema->string()->required(),
                'description' => $schema->string()->required(),
                'learning_objectives' => $schema->array()->items($schema->string())->required(),
                'estimated_duration_minutes' => $schema->integer()->required(),
            ])->withoutAdditionalProperties())->required(),
        ];
    }
}
