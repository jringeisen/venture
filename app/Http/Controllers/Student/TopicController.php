<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PromptQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TopicController extends Controller
{
    public function show(Request $request, string $topic)
    {
        return Inertia::render('Student/Topic', [
            'date' => $request->date,
            'topic' => Str::slug($topic),
            'questions' => PromptQuestion::where('student_id', $request->user()->id)
                ->whereHas('promptAnswer', function ($query) use ($topic) {
                    $query->where('subject_category', Str::slug($topic));
                })
                ->with('promptAnswer')
                ->filterByDate($request)
                ->get()
                ->map(function ($promptQuestion) {
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
