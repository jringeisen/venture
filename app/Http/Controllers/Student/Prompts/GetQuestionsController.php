<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Models\Prompt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\OpenAIService;

class GetQuestionsController extends Controller
{
    public function __invoke(Request $request, OpenAIService $openAIService)
    {
        $response = $openAIService
            ->messages('system', Prompt::where('category', 'questions')->first()->prompt)
            ->messages('user', $request->question)
            ->user($request->user())
            ->create();

        $question = $request->user()->promptQuestions()->latest()->first();

        $question->promptAnswer()->updateOrCreate([
            'prompt_question_id' => $question->id,
        ], [
            'questions' => $response['questions'],
        ]);

        return response()->json($response);
    }
}
