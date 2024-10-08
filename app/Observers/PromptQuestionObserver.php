<?php

namespace App\Observers;

use App\Models\PromptQuestion;
use Illuminate\Support\Carbon;

class PromptQuestionObserver
{
    public function creating(PromptQuestion $promptQuestion): void
    {
        $user = request()->user();

        $lastQuestionDate = $user->promptQuestions()->latest()->first()?->created_at;

        if ($lastQuestionDate && $lastQuestionDate->isSameDay(Carbon::today())) {
            return;
        }

        if ($lastQuestionDate && $lastQuestionDate->isSameDay(Carbon::yesterday())) {
            $user->increment('current_streak');
        } else {
            $user->current_streak = 1;
        }

        $user->save();
    }
}
