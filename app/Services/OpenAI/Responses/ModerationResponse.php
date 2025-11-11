<?php

namespace App\Services\OpenAI\Responses;

class ModerationResponse
{
    public function __construct(
        public readonly bool $flagged,
        public readonly ?string $message = null
    ) {
    }

    public static function fromOpenAI(object $result): self
    {
        return new self(
            flagged: $result->flagged,
            message: null
        );
    }
}
