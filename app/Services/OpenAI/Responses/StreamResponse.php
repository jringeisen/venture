<?php

namespace App\Services\OpenAI\Responses;

use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamResponse
{
    public function __construct(
        public readonly StreamedResponse $stream
    ) {
    }
}
