<?php

namespace App\Rules;

use App\Ai\Agents\ModerationAgent;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Moderate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = ModerationAgent::make()->prompt($value);

        if ($response['flagged'] === true) {
            $fail($response['message'] ?? 'This question violates our content policies. Please try another question.');
        }
    }
}
