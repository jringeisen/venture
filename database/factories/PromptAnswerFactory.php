<?php

namespace Database\Factories;

use App\Models\PromptQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromptAnswerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'prompt_question_id' => PromptQuestion::factory()->create(),
            'subject_category' => $this->faker->word,
            'content' => $this->faker->paragraph,
            'questions' => $this->createArrayOfQuestions(),
            'word_count' => 0,
        ];
    }

    protected function createArrayOfQuestions(): array
    {
        $questions = [];

        for ($i = 0; $i < 15; $i++) {
            $questions[] = $this->faker->sentence;
        }

        return $questions;
    }
}
