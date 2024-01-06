<?php

namespace App\Rules;

use App\Models\Prompt;
use App\Services\OpenAIService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use OpenAI\Laravel\Facades\OpenAI;

class Moderate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $moderation = OpenAI::moderations()->create([
            'model' => 'text-moderation-latest',
            'input' => $value,
        ]);

        if ($moderation->results[0]->flagged === true) {
            $fail('This question violates OpenAI\'s policies. Please try another question.');
        }

        $response = (new OpenAIService)
            ->messages('system', Prompt::where('category', 'moderation')->first()->prompt)
            ->messages('user', $value)
            ->user(request()->user())
            ->create();

        if (isset($response['flagged']) && $response['flagged'] === true) {
            $fail($response['message']);
        }
    }
}
