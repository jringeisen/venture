<?php

namespace App\Http\Resources;

use App\Models\PromptQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'current_streak' => $this->current_streak,
            'prompt_questions' => $this->promptQuestions()
                ->whereHas('promptAnswer')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->through(function (PromptQuestion $promptQuestion) use ($request) {
                    return [
                        'id' => $promptQuestion->id,
                        'question' => $promptQuestion->question,
                        'prompt_answer' => [
                            'id' => $promptQuestion->promptAnswer->id,
                            'subject_category' => $promptQuestion->promptAnswer->subject_category,
                            'content' => $promptQuestion->promptAnswer->content,
                            'word_count' => $promptQuestion->promptAnswer->word_count,
                        ],
                        'created_at' => $promptQuestion->created_at->timezone($request->user()->timezone)->toFormattedDateString(),
                    ];
                }),
        ];
    }
}
