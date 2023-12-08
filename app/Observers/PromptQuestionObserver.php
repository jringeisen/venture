<?php

namespace App\Observers;

use App\Models\PromptQuestion;
use Illuminate\Support\Carbon;

class PromptQuestionObserver
{
    public function creating(PromptQuestion $promptQuestion): void
    {
        $user = request()->user();

        $lastQuestionDate = $user->promptQuestions()->latest()->first()->created_at;

        if ($lastQuestionDate->isSameDay(Carbon::today())) {
            return;
        }

        if ($lastQuestionDate->isSameDay(Carbon::yesterday())) {
            $user->current_streak->increment();
        } else {
            $user->current_streak = 1;
        }

        $user->save();
    }
}
