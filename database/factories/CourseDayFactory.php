<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\CoursePrompt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseDay>
 */
class CourseDayFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_prompt_id' => CoursePrompt::factory(),
            'day_number' => fake()->numberBetween(1, 5),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'content' => null,
            'content_status' => ContentStatus::Pending,
            'learning_objectives' => [
                fake()->sentence(),
                fake()->sentence(),
            ],
            'estimated_duration_minutes' => fake()->randomElement([10, 15, 20]),
        ];
    }

    public function withContent(): static
    {
        return $this->state(fn (array $attributes) => [
            'content' => fake()->paragraphs(3, true),
            'content_status' => ContentStatus::Completed,
            'trivia_questions' => [
                [
                    'question' => fake()->sentence().'?',
                    'option_a' => fake()->word(),
                    'option_b' => fake()->word(),
                    'option_c' => fake()->word(),
                    'option_d' => fake()->word(),
                    'correct_answer' => 0,
                ],
            ],
        ]);
    }

    public function forDay(int $dayNumber): static
    {
        return $this->state(fn (array $attributes) => [
            'day_number' => $dayNumber,
        ]);
    }
}
