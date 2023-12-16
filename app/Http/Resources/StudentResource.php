<?php

namespace App\Http\Resources;

use App\Models\PromptQuestion;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
                ->when($request->input('date', now($request->user()->timezone)->toDateString()), function (Builder $query, string $date) {
                    $startOfDayUtc = Carbon::parse($date, auth()->user()->timezone)->startOfDay()->utc();
                    $endOfDayUtc = Carbon::parse($date, auth()->user()->timezone)->endOfDay()->utc();

                    $query->whereBetween('created_at', [$startOfDayUtc, $endOfDayUtc]);
                })
                ->get()
                ->map(function (PromptQuestion $promptQuestion) use ($request) {
                    return [
                        'id' => $promptQuestion->id,
                        'question' => $promptQuestion->question,
                        'prompt_answer' => $promptQuestion->promptAnswer,
                        'created_at' => $promptQuestion->created_at->timezone($request->user()->timezone)->toFormattedDateString(),
                    ];
                }),
        ];
    }
}
