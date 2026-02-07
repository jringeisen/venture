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
class LessonContentGenerationAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are an expert K-12 curriculum designer and educator with deep knowledge of pedagogy, child development, and engaging content creation. You create comprehensive, well-structured lessons that genuinely teach students. Your content is thorough, uses multiple teaching strategies (examples, analogies, visuals, activities), and makes learning memorable and enjoyable. Always respond with valid JSON only.';
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'content' => $schema->string()->required(),
            'trivia_questions' => $schema->array()->items($schema->object([
                'question' => $schema->string()->required(),
                'option_a' => $schema->string()->required(),
                'option_b' => $schema->string()->required(),
                'option_c' => $schema->string()->required(),
                'option_d' => $schema->string()->required(),
                'correct_answer' => $schema->integer()->required(),
                'difficulty' => $schema->string()->nullable()->required(),
            ])->withoutAdditionalProperties())->required(),
        ];
    }
}
