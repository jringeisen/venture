<?php

namespace Database\Seeders;

use App\Enums\AttendanceType;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Seed attendance data for the first parent/student pair.
     * Run with: php artisan db:seed --class=AttendanceSeeder
     */
    public function run(): void
    {
        $parent = User::whereNull('parent_id')->first();
        $student = User::where('parent_id', $parent?->id)->first();

        if (! $parent || ! $student) {
            $this->command->warn('No parent/student pair found. Skipping attendance seeder.');

            return;
        }

        $this->command->info("Seeding attendance data for student '{$student->name}'...");

        $existingDates = Attendance::where('user_id', $student->id)
            ->pluck('date')
            ->map(fn ($d) => $d instanceof \Carbon\Carbon ? $d->toDateString() : $d)
            ->toArray();

        for ($i = 90; $i >= 1; $i--) {
            $date = now()->subDays($i)->toDateString();

            if (in_array($date, $existingDates)) {
                continue;
            }

            $dayOfWeek = now()->subDays($i)->dayOfWeek;

            // Skip weekends
            if ($dayOfWeek === 0 || $dayOfWeek === 6) {
                continue;
            }

            // ~85% attendance rate on weekdays
            if (fake()->boolean(85)) {
                $type = fake()->randomElement([
                    AttendanceType::Present,
                    AttendanceType::Present,
                    AttendanceType::Present,
                    AttendanceType::Present,
                    AttendanceType::FieldTrip,
                    AttendanceType::OfflineDay,
                ]);

                $notes = match ($type) {
                    AttendanceType::FieldTrip => fake()->randomElement([
                        'Science museum field trip',
                        'Nature center visit',
                        'Historical site tour',
                        'Art gallery visit',
                    ]),
                    AttendanceType::OfflineDay => fake()->randomElement([
                        'Library research day',
                        'Hands-on science project',
                        'Reading and journaling',
                    ]),
                    default => null,
                };

                Attendance::create([
                    'user_id' => $student->id,
                    'date' => $date,
                    'type' => $type,
                    'notes' => $notes,
                    'created_by' => $type === AttendanceType::Present ? null : $parent->id,
                ]);
            } elseif (fake()->boolean(30)) {
                Attendance::create([
                    'user_id' => $student->id,
                    'date' => $date,
                    'type' => AttendanceType::ExcusedAbsence,
                    'notes' => fake()->randomElement(['Doctor appointment', 'Family event', 'Not feeling well']),
                    'created_by' => $parent->id,
                ]);
            }
        }

        $this->command->info('Attendance seed data created successfully.');
    }
}
