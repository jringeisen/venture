<?php

namespace App\Services;

use App\Models\PromptQuestion;

class WordCountService
{
    public function calculateTotalWordsRead(mixed $user, string $timeframe): string
    {
        $count = $user->promptQuestions()
            ->filterByTimeframe($timeframe)
            ->whereHas('promptAnswer')
            ->get()
            ->reduce(function (int $carry, PromptQuestion $promptQuestion) {
                return $carry + (int) $promptQuestion->promptAnswer->word_count;
            }, 0);

        return number_format($count);
    }
}
