<?php

namespace App\Rules;

use App\Models\Prompt;
use App\Services\OpenAI\OpenAIChatService;
use App\Services\OpenAI\OpenAIModerationService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Moderate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $moderationService = app(OpenAIModerationService::class);
        $chatService = app(OpenAIChatService::class);

        // First check with OpenAI's built-in moderation API
        $moderationResponse = $moderationService->moderate($value);

        if ($moderationResponse->flagged === true) {
            $fail('This question violates OpenAI\'s policies. Please try another question.');
        }

        // Then check with custom moderation prompt
        $response = $chatService
            ->addMessage('system', Prompt::where('category', 'moderation')->first()->prompt)
            ->addMessage('user', $value)
            ->createChat();

        if (property_exists($response, 'moderation') && $response->moderation->flagged === true) {
            $fail($response->moderation->message);
        }
    }
}
