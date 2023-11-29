<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::first()->id,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(),
            'grade' => fake()->numberBetween(1, 12),
            'age' => fake()->numberBetween(6, 18),
        ];
    }
}
