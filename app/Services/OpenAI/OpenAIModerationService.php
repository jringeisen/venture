<?php

namespace App\Services\OpenAI;

use App\Services\OpenAI\Responses\ModerationResponse;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIModerationService
{
    protected const MODEL = 'omni-moderation-latest';

    public function moderate(string $input): ModerationResponse
    {
        $moderation = OpenAI::moderations()
            ->create([
                'model' => self::MODEL,
                'input' => $input,
            ]);

        return ModerationResponse::fromOpenAI($moderation->results[0]);
    }
}
