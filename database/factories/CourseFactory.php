<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            'Mathematics',
            'Science',
            'History',
            'Literature',
            'Computer Science',
            'Art',
            'Music',
            'Geography',
            'Biology',
            'Physics',
        ];

        $subject = fake()->randomElement($subjects);

        return [
            'title' => $subject.': '.fake()->sentence(3),
            'description' => fake()->paragraphs(2, true),
            'length_in_weeks' => fake()->numberBetween(4, 12),
        ];
    }

    public function withWeeks(int $weeks): static
    {
        return $this->state(fn (array $attributes) => [
            'length_in_weeks' => $weeks,
        ]);
    }

    public function elementary(): static
    {
        return $this->state(fn (array $attributes) => [
            'min_age' => 5,
            'max_age' => 10,
        ]);
    }

    public function middle(): static
    {
        return $this->state(fn (array $attributes) => [
            'min_age' => 11,
            'max_age' => 13,
        ]);
    }

    public function high(): static
    {
        return $this->state(fn (array $attributes) => [
            'min_age' => 14,
            'max_age' => 18,
        ]);
    }

    public function generating(): static
    {
        return $this->state(fn (array $attributes) => [
            'generation_status' => ContentStatus::Generating,
        ]);
    }

    public function generatingContent(): static
    {
        return $this->state(fn (array $attributes) => [
            'content_generation_status' => ContentStatus::Generating,
        ]);
    }
}
