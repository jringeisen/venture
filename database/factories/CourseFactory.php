<?php

namespace Database\Factories;

use App\Enums\CourseLengths;
use App\Enums\CourseLevels;
use App\Enums\CourseSubjects;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence;

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph,
            'length' => CourseLengths::getRandomValue(),
            'image' => $this->faker->imageUrl(),
            'creators_name' => $this->faker->name,
            'level' => CourseLevels::getRandomValue(),
            'subject' => CourseSubjects::getRandomValue(),
        ];
    }
}
