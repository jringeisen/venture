<?php

namespace App\Services\OpenAI\Responses;

class SubjectResponse
{
    public function __construct(
        public readonly string $subject,
        public readonly ?string $subCategory = null
    ) {
    }

    public static function fromJson(object $response): self
    {
        return new self(
            subject: $response->subject,
            subCategory: $response->sub_category ?? null
        );
    }
}
