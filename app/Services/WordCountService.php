<?php

namespace App\Services;

use App\Models\PromptAnswer;

class WordCountService
{
    public function calculateTotalWordsRead(string $timeframe, mixed $userId = null): string
    {
        $count = PromptAnswer::query()
            ->when($userId, fn ($query) => $query->whereHas('promptQuestion', fn ($query) => $query->where('user_id', $userId)))
            ->when(! $userId, fn ($query) => $query->whereHas('promptQuestion.user', fn ($query) => $query->where('parent_id', request()->user()->id)))
            ->filterByTimeframe($timeframe)
            ->sum('word_count');

        return number_format($count);
    }
}
