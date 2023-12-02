<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use App\Services\OpenAIService;
use Illuminate\Http\Request;

class GetSubjectController extends Controller
{
    public function __invoke(Request $request, OpenAIService $openAIService)
    {
        $question = $request->user()->promptQuestions()->latest()->first();

        $response = $openAIService
            ->messages('system', Prompt::where('category', 'categorize')->first()->prompt)
            ->messages('user', $request->question)
            ->user($request->user())
            ->updateQuestionTokens($question)
            ->create();

        $question->promptAnswer()->updateOrCreate([
            'prompt_question_id' => $question->id,
        ], [
            'subject_category' => strtolower($response['subject']),
        ]);

        return response()->json($response);
    }
}
