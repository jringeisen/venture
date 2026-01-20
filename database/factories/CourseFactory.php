<?php

namespace Database\Factories;

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
}
