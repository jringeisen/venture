<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoursePrompt>
 */
class CoursePromptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'week_number' => fake()->numberBetween(1, 12),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'prompt_text' => fake()->paragraphs(2, true),
            'learning_objectives' => [
                fake()->sentence(),
                fake()->sentence(),
                fake()->sentence(),
            ],
            'trivia_questions' => [
                [
                    'question' => fake()->sentence().'?',
                    'answers' => [
                        ['text' => fake()->word(), 'correct' => true],
                        ['text' => fake()->word(), 'correct' => false],
                        ['text' => fake()->word(), 'correct' => false],
                        ['text' => fake()->word(), 'correct' => false],
                    ],
                ],
            ],
            'additional_resources' => [
                ['title' => fake()->sentence(3), 'url' => fake()->url()],
            ],
            'estimated_duration_minutes' => fake()->randomElement([15, 30, 45, 60]),
        ];
    }

    public function forWeek(int $weekNumber): static
    {
        return $this->state(fn (array $attributes) => [
            'week_number' => $weekNumber,
        ]);
    }
}
