<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Marine Biology',
                'slug' => 'marine-biology',
                'description' => 'Explore the diverse and complex life systems in marine environments.',
            ],
            [
                'name' => 'Creative Writing',
                'slug' => 'creative-writing',
                'description' => 'Unleash your imagination and learn the art of storytelling.',
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
                'description' => 'Discover the world through scientific inquiry and experimentation.',
            ],
            [
                'name' => 'Mathematics',
                'slug' => 'mathematics',
                'description' => 'Understand the universal language of numbers and equations.',
            ],
            [
                'name' => 'Physics',
                'slug' => 'physics',
                'description' => 'Explore the fundamental principles governing the natural world.',
            ],
            [
                'name' => 'Chemistry',
                'slug' => 'chemistry',
                'description' => 'Study the composition, structure, and properties of matter.',
            ],
            [
                'name' => 'Biology',
                'slug' => 'biology',
                'description' => 'Explore the science of life and living organisms.',
            ],
            [
                'name' => 'Astronomy',
                'slug' => 'astronomy',
                'description' => 'Journey through the universe and study celestial phenomena.',
            ],
            [
                'name' => 'Geology',
                'slug' => 'geology',
                'description' => 'Study the Earth, its materials, and the processes affecting it.',
            ],
            [
                'name' => 'Social Studies',
                'slug' => 'social-studies',
                'description' => 'Examine human society and social relationships.',
            ],
            [
                'name' => 'History',
                'slug' => 'history',
                'description' => 'Dive into the past to understand the present and shape the future.',
            ],
            [
                'name' => 'Geography',
                'slug' => 'geography',
                'description' => 'Explore the physical features of the Earth and its inhabitants.',
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Understand the world of commerce, finance, and entrepreneurship.',
            ],
            [
                'name' => 'Language Arts',
                'slug' => 'language-arts',
                'description' => 'Enhance your communication skills through reading, writing, and analysis.',
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate([
                'name' => $category['name'],
            ], $category);
        }
    }
}
