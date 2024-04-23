<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->seedData() as $data) {
            if (Course::where('slug', $data['slug'])->exists()) {
                continue;
            }

            Course::factory()->create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'length' => $data['length'],
                'image' => $data['image_url'],
                'creators_name' => $data['creators_name'],
                'level' => $data['level'],
                'subject' => $data['subject'],
            ]);
        }
    }

    protected function seedData(): array
    {
        return [
            [
                'title' => 'Introduction to Algebra',
                'slug' => 'introduction-to-algebra',
                'description' => 'Explore basic algebraic concepts and learn how to apply them in real-life situations.',
                'length' => '36_weeks',
                'image_url' => 'https://images.pexels.com/photos/6238297/pexels-photo-6238297.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                'creators_name' => 'John Doe',
                'level' => 'middle',
                'subject' => 'mathematics',
            ],
            [
                'title' => 'Fundamentals of Chemistry',
                'slug' => 'fundamentals-of-chemistry',
                'description' => 'Dive into the essentials of chemical reactions, bonding, and the periodic table.',
                'length' => '18_weeks',
                'image_url' => 'https://images.pexels.com/photos/220989/pexels-photo-220989.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                'creators_name' => 'Alice Johnson',
                'level' => 'high',
                'subject' => 'applied_sciences',
            ],
            [
                'title' => 'World History: Ancient Civilizations',
                'slug' => 'world-history-ancient-civilizations',
                'description' => 'Learn about ancient societies from around the globe and their lasting impacts on modern cultures.',
                'length' => '9_weeks',
                'image_url' => 'https://images.pexels.com/photos/9390722/pexels-photo-9390722.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                'creators_name' => 'Michael Brown',
                'level' => 'middle',
                'subject' => 'history',
            ],
            [
                'title' => 'Creative Writing: Poetry',
                'slug' => 'creative-writing-poetry',
                'description' => 'Express yourself through the art of poetry and develop your own writing style.',
                'length' => '1_week',
                'image_url' => 'https://images.pexels.com/photos/4218588/pexels-photo-4218588.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                'creators_name' => 'Samantha Ray',
                'level' => 'high',
                'subject' => 'english',
            ],
            [
                'title' => 'Environmental Science and Ecology',
                'slug' => 'environmental-science-and-ecology',
                'description' => 'Understand the relationships between organisms and their environments, and explore sustainability.',
                'length' => '36_weeks',
                'image_url' => 'https://images.pexels.com/photos/414807/pexels-photo-414807.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                'creators_name' => 'David Smith',
                'level' => 'middle',
                'subject' => 'natural_sciences',
            ],
        ];
    }
}
