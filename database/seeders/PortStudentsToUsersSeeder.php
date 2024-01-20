<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortStudentsToUsersSeeder extends Seeder
{
    public function run(): void
    {
        Student::all()->each(function ($student) {
            User::create([
                'parent_id' => $student->user_id,
                'name' => $student->name,
                'email' => $student->email,
                'username' => $student->username,
                'grade' => $student->grade,
                'age' => $student->age,
                'motivational_message' => $student->motivational_message,
                'current_streak' => $student->current_streak,
                'password' => $student->password,
                'timezone' => $student->timezone,
            ]);
        });
    }
}
