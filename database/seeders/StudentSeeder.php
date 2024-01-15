<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'user_id' => User::first()->id,
            'name' => 'Test Student',
            'username' => 'teststudent',
            'password' => Hash::make('password'),
            'age' => 10,
            'grade' => 5,
        ]);

        Student::factory()->count(50)->create();
    }
}
