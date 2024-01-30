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
                'meta_title' => 'Marine Biology Studies',
                'meta_description' => "Delve into marine biology, exploring the ocean\'s ecosystems, species, and environmental impact.",
            ],
            [
                'name' => 'Creative Writing',
                'slug' => 'creative-writing',
                'description' => 'Unleash your imagination and learn the art of storytelling.',
                'meta_title' => 'Creative Writing Techniques',
                'meta_description' => 'Master the art of creative writing, from narrative development to character creation.',
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
                'description' => 'Discover the world through scientific inquiry and experimentation.',
                'meta_title' => 'Science Exploration',
                'meta_description' => 'Engage with diverse scientific disciplines and uncover the mysteries of the natural world.',
            ],
            [
                'name' => 'Mathematics',
                'slug' => 'mathematics',
                'description' => 'Understand the universal language of numbers and equations.',
                'meta_title' => 'Mathematics Learning',
                'meta_description' => 'Explore mathematical concepts, theories, and their practical applications.',
            ],
            [
                'name' => 'Physics',
                'slug' => 'physics',
                'description' => 'Explore the fundamental principles governing the natural world.',
                'meta_title' => 'Physics Fundamentals',
                'meta_description' => 'Dive into physics, from quantum mechanics to classical theories.',
            ],
            [
                'name' => 'Chemistry',
                'slug' => 'chemistry',
                'description' => 'Study the composition, structure, and properties of matter.',
                'meta_title' => 'Chemistry Studies',
                'meta_description' => 'Understand chemical reactions, elements, and the molecular nature of matter.',
            ],
            [
                'name' => 'Biology',
                'slug' => 'biology',
                'description' => 'Explore the science of life and living organisms.',
                'meta_title' => 'Biology Insights',
                'meta_description' => 'Discover the complexities of biological systems and organisms.',
            ],
            [
                'name' => 'Astronomy',
                'slug' => 'astronomy',
                'description' => 'Journey through the universe and study celestial phenomena.',
                'meta_title' => 'Astronomy Discoveries',
                'meta_description' => 'Explore the cosmos, from planets and stars to galaxies and black holes.',
            ],
            [
                'name' => 'Geology',
                'slug' => 'geology',
                'description' => 'Study the Earth, its materials, and the processes affecting it.',
                'meta_title' => 'Geology Exploration',
                'meta_description' => "Understand Earth\'s history, structure, and dynamic changes.",
            ],
            [
                'name' => 'Social Studies',
                'slug' => 'social-studies',
                'description' => 'Examine human society and social relationships.',
                'meta_title' => 'Social Studies Learning',
                'meta_description' => 'Explore various aspects of human society, culture, and history.',
            ],
            [
                'name' => 'History',
                'slug' => 'history',
                'description' => 'Dive into the past to understand the present and shape the future.',
                'meta_title' => 'History Lessons',
                'meta_description' => 'Uncover the events, figures, and movements that shaped our world.',
            ],
            [
                'name' => 'Geography',
                'slug' => 'geography',
                'description' => 'Explore the physical features of the Earth and its inhabitants.',
                'meta_title' => 'Geography Studies',
                'meta_description' => "Learn about the Earth\'s landscapes, peoples, and environments.",
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Understand the world of commerce, finance, and entrepreneurship.',
                'meta_title' => 'Business Insights',
                'meta_description' => 'Navigate the complexities of the business world and its economic principles.',
            ],
            [
                'name' => 'Language Arts',
                'slug' => 'language-arts',
                'description' => 'Enhance your communication skills through reading, writing, and analysis.',
                'meta_title' => 'Language Arts Education',
                'meta_description' => 'Develop critical reading, writing, and communication skills.',
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate([
                'name' => $category['name'],
            ], $category);
        }
    }
}
