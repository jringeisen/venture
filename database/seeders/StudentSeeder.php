<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->student()->create([
            'parent_id' => User::whereNull('parent_id')->first()->id,
            'name' => 'Test Student',
            'username' => 'teststudent',
        ]);

        User::factory()->student()->count(50)->create();
    }
}
