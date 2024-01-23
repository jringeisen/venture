<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'timezone' => 'America/New_York',
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function parent(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => null,
            'email' => fake()->unique()->safeEmail(),
        ]);
    }

    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => User::factory()->parent()->create()->id,
            'username' => fake()->userName(),
            'grade' => fake()->numberBetween(1, 12),
            'age' => fake()->numberBetween(6, 18),
        ]);
    }
}
