<?php

namespace App\Http\Resources;

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
            'prompt_questions' => $this->promptQuestions()
                ->whereHas('promptAnswer')
                ->filterByDate($date)
                ->get()
                ->map(function ($promptQuestion) {
                    return [
                        'id' => $promptQuestion->id,
                        'question' => $promptQuestion->question,
                        'prompt_answer' => $promptQuestion->promptAnswer,
                        'created_at' => $promptQuestion->created_at->setTimezone('America/New_York')->toFormattedDateString(),
                    ];
                }),
        ];
    }
}
