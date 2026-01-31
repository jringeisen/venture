<?php

namespace Database\Factories;

use App\Enums\ComplianceReportStatus;
use App\Enums\ComplianceState;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComplianceReport>
 */
class ComplianceReportFactory extends Factory
{
    public function definition(): array
    {
        $parent = User::factory()->parent();

        return [
            'parent_id' => $parent,
            'student_id' => User::factory()->state(['parent_id' => $parent]),
            'state' => ComplianceState::FL,
            'title' => 'Compliance Report - '.fake()->monthName().' '.fake()->year(),
            'period_start' => fake()->dateTimeBetween('-6 months', '-3 months'),
            'period_end' => fake()->dateTimeBetween('-3 months', 'now'),
            'summary_statistics' => [
                'total_instruction_days' => fake()->numberBetween(30, 180),
                'total_instruction_hours' => fake()->randomFloat(1, 50, 500),
                'courses_active' => fake()->numberBetween(1, 5),
                'courses_completed' => fake()->numberBetween(0, 3),
                'average_trivia_score' => fake()->randomFloat(1, 60, 100),
                'total_interactions' => fake()->numberBetween(10, 200),
            ],
            'report_metadata' => null,
            'status' => ComplianceReportStatus::Generated,
            'generated_at' => now(),
        ];
    }

    public function reviewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ComplianceReportStatus::Reviewed,
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ComplianceReportStatus::Submitted,
        ]);
    }

    public function forParentAndStudent(User $parent, User $student): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
            'student_id' => $student->id,
        ]);
    }
}
