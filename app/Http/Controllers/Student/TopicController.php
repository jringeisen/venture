<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PromptQuestion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TopicController extends Controller
{
    public function show(Request $request, string $topic): Response
    {
        $date = $request->date ?? now()->toDateString();

        return Inertia::render('Student/Topic', [
            'date' => $date,
            'topic' => Str::slug($topic),
            'questions' => PromptQuestion::where('student_id', $request->user()->id)
                ->whereHas('promptAnswer', function (Builder $query) use ($topic) {
                    $query->where('subject_category', Str::replace('-', ' ', $topic));
                })
                ->with('promptAnswer')
                ->orderBy('created_at', 'desc')
                ->filterByDate($date)
                ->get()
                ->map(function (PromptQuestion $promptQuestion) {
                    return [
                        'id' => $promptQuestion->id,
                        'question' => $promptQuestion->question,
                        'prompt_answer' => $promptQuestion->promptAnswer,
                        'created_at' => $promptQuestion->created_at->toFormattedDateString(),
                    ];
                }),
        ]);
    }
}
