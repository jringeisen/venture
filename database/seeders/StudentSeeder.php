<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'user_id' => User::first()->id,
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
        ]);

        Student::factory()->count(50)->create();
    }
}
