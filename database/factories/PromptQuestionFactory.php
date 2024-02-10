<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromptQuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(),
            'question' => $this->faker->sentence,
            'total_tokens' => $this->faker->numberBetween(1, 500),
        ];
    }
}
