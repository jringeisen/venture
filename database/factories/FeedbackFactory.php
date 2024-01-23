<?php

namespace Database\Factories;

use App\Enums\FeedbackStatuses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => collect(FeedbackStatuses::cases())->random(),
        ];
    }
}
