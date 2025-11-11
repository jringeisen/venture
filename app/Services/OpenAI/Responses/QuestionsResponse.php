<?php

namespace App\Services\OpenAI\Responses;

class QuestionsResponse
{
    /**
     * @param  array<string>  $questions
     */
    public function __construct(
        public readonly array $questions
    ) {
    }

    public static function fromJson(object $response): self
    {
        return new self(
            questions: $response->questions
        );
    }
}
