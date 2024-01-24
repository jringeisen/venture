<?php

namespace App\Rules;

use App\Models\Prompt;
use App\Services\Interfaces\AIServiceInterface;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\ValidationRule;

class Moderate implements ValidationRule
{
    private readonly AIServiceInterface $service;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->service = app()->make(AIServiceInterface::class, ['aiService' => 'OpenAI']);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $moderationDto = $this->service->moderate($value);

        if ($moderationDto->moderation->flagged === true) {
            $fail('This question violates OpenAI\'s policies. Please try another question.');
        }

        $response = $this->service
            ->addMessage('system', Prompt::where('category', 'moderation')->first()->prompt)
            ->addMessage('user', $value)
            ->createChat();

        if (property_exists($response, 'moderation') && $response->moderation->flagged === true) {
            $fail($response->moderation->message);
        }
    }
}
