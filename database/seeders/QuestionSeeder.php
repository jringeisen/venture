<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'text' => 'What are the challenges of space exploration?',
                'category' => 'Science',
                'sub_category' => 'Physics',
                'grade' => 10,
                'image' => 'https://images.pexels.com/photos/73871/rocket-launch-rocket-take-off-nasa-73871.jpeg',
            ],
            [
                'text' => 'How do chamelons change their color?',
                'category' => 'Science',
                'sub_category' => 'Biology',
                'grade' => 5,
                'image' => 'https://images.pexels.com/photos/62289/yemen-chameleon-chamaeleo-calyptratus-chameleon-reptile-62289.jpeg',
            ],
            [
                'text' => 'How did the egyptians build the pyramids?',
                'category' => 'Social Studies',
                'sub_category' => 'History',
                'grade' => 7,
                'image' => 'https://images.pexels.com/photos/3185480/pexels-photo-3185480.jpeg',
            ],
            [
                'text' => 'What are the most endangered animals?',
                'category' => 'Science',
                'sub_category' => 'Biology',
                'grade' => 9,
                'image' => 'https://images.pexels.com/photos/38280/monkey-mandril-africa-baboon-38280.jpeg',
            ],
            [
                'text' => 'How do x-rays work to see inside our bodies?',
                'category' => 'Science',
                'sub_category' => 'Physics',
                'grade' => 3,
                'image' => 'https://images.pexels.com/photos/207496/pexels-photo-207496.jpeg',
            ],
            [
                'text' => 'What is the significance of the colossuem?',
                'category' => 'Social Studies',
                'sub_category' => 'History',
                'grade' => 10,
                'image' => 'https://images.pexels.com/photos/17745800/pexels-photo-17745800/free-photo-of-wall-of-colosseum.jpeg',
            ],
            [
                'text' => 'What causes northern lights to show at night?',
                'category' => 'Science',
                'sub_category' => 'Physics',
                'grade' => 10,
                'image' => 'https://images.pexels.com/photos/1819650/pexels-photo-1819650.jpeg',
            ],
            [
                'text' => 'Did water form the Grand Canyon?',
                'category' => 'Science',
                'sub_category' => 'Geographical Sciences',
                'grade' => 6,
                'image' => 'https://images.pexels.com/photos/45839/antelope-canyon-arizona-sandstone-rock-45839.jpeg',
            ],
            [
                'text' => 'How do bees make honey?',
                'category' => 'Science',
                'sub_category' => 'Biology',
                'grade' => 2,
                'image' => 'https://images.pexels.com/photos/3424406/pexels-photo-3424406.jpeg',
            ],
            [
                'text' => 'What do jelly fish eat?',
                'category' => 'Science',
                'sub_category' => 'Biology',
                'grade' => 3,
                'image' => 'https://images.pexels.com/photos/1784578/pexels-photo-1784578.jpeg',
            ],
        ];

        foreach ($questions as $question) {
            Question::updateOrCreate(
                ['text' => $question['text']],
                [
                    'category' => $question['category'],
                    'sub_category' => $question['sub_category'],
                    'grade' => $question['grade'],
                    'image' => $question['image'],
                ]
            );
        }
    }
}
