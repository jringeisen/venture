<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Models\Prompt;
use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Http\Controllers\Controller;

class GetSubjectController extends Controller
{
    public function __invoke(Request $request, OpenAIService $openAIService)
    {
        $response = $openAIService
            ->messages('system', Prompt::where('category', 'categorize')->first()->prompt)
            ->messages('user', $request->question)
            ->user($request->user())
            ->create();

        $question = $request->user()->promptQuestions()->latest()->first();

        $question->promptAnswer()->updateOrCreate([
            'prompt_question_id' => $question->id,
        ], [
            'subject_category' => strtolower($response['subject']),
        ]);

        return response()->json($response);
    }
}
