<?php

namespace App\Services\OpenAI\Responses;

class ChatResponse
{
    public function __construct(
        public readonly ?string $subject = null,
        public readonly ?string $subCategory = null,
        public readonly ?array $questions = null,
        public readonly ?ModerationResponse $moderation = null,
        public readonly ?string $message = null
    ) {
    }

    public static function fromJson(object $response): self
    {
        // Handle moderation response
        if (property_exists($response, 'flagged')) {
            return new self(
                moderation: new ModerationResponse(
                    flagged: $response->flagged,
                    message: $response->flagged ? ($response->message ?? null) : null
                )
            );
        }

        // Handle subject categorization response
        if (property_exists($response, 'subject')) {
            return new self(
                subject: $response->subject,
                subCategory: $response->sub_category ?? null
            );
        }

        // Handle questions response
        if (property_exists($response, 'questions')) {
            return new self(
                questions: $response->questions
            );
        }

        // Handle string message
        if (is_string($response)) {
            return new self(
                message: $response
            );
        }

        return new self();
    }
}
