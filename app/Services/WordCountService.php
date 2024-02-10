<?php

namespace App\Services;

use App\Models\PromptQuestion;

class WordCountService
{
    public function calculateTotalWordsRead(string $timeframe, mixed $userId = null): string
    {
        $count = PromptQuestion::query()
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->when(! $userId, fn ($query) => $query->whereHas('user', fn ($query) => $query->where('parent_id', request()->user()->id)))
            ->filterByTimeframe($timeframe)
            ->whereHas('promptAnswer')
            ->get()
            ->reduce(function (int $carry, PromptQuestion $promptQuestion) {
                return $carry + (int) $promptQuestion->promptAnswer->word_count;
            }, 0);

        return number_format($count);
    }
}
