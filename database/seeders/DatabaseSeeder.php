<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->parent()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
        ]);

        $this->call([
            StudentSeeder::class,
            PromptSeeder::class,
            TimezoneSeeder::class,
            QuestionSeeder::class,
        ]);
    }
}
