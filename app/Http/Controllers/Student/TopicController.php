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
        return Inertia::render('Student/Topic', [
            'topic' => Str::slug($topic),
            'questions' => PromptQuestion::where('student_id', $request->user()->id)
                ->when(
                    $topic !== 'all',
                    fn($query) => $query->whereHas('promptAnswer', function (Builder $query) use ($topic) {
                        $query->where('subject_category', Str::replace('-', ' ', $topic));
                    })
                )
                ->with('promptAnswer')
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
        ]);
    }
}
