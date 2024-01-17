<?php

namespace App\Services;

use App\Models\PromptQuestion;

class WordCountService
{
    public function calculateWordsForPromptAnswers(mixed $user): string
    {
        $count = $user->promptQuestions()
            ->whereHas('promptAnswer')
            ->get()
            ->reduce(function (int $carry, PromptQuestion $promptQuestion) {
                return $carry + (int) $promptQuestion->promptAnswer->word_count;
            }, 0);

        return number_format($count);
    }
}
