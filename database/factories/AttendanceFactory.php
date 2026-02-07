<?php

namespace Database\Factories;

use App\Enums\AttendanceType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'date' => fake()->dateTimeBetween('-90 days', 'now')->format('Y-m-d'),
            'type' => AttendanceType::Present,
            'notes' => null,
            'created_by' => null,
        ];
    }

    public function fieldTrip(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => AttendanceType::FieldTrip,
            'notes' => fake()->sentence(),
        ]);
    }

    public function offlineDay(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => AttendanceType::OfflineDay,
            'notes' => fake()->sentence(),
        ]);
    }

    public function excusedAbsence(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => AttendanceType::ExcusedAbsence,
            'notes' => fake()->sentence(),
        ]);
    }

    public function forStudent(User $student): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $student->id,
        ]);
    }
}
