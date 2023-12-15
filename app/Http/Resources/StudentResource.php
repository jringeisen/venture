<?php

namespace App\Http\Resources;

use App\Models\PromptQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $date = $request->date ?? now()->toDateString();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'current_streak' => $this->current_streak,
            'prompt_questions' => $this->promptQuestions()
                ->whereHas('promptAnswer')
                ->filterByDate($date)
                ->get()
                ->map(function (PromptQuestion $promptQuestion) use ($request) {
                    return [
                        'id' => $promptQuestion->id,
                        'question' => $promptQuestion->question,
                        'prompt_answer' => $promptQuestion->promptAnswer,
                        'created_at' => $promptQuestion->created_at->setTimezone($request->user()->timezone)->toFormattedDateString(),
                    ];
                }),
        ];
    }
}
