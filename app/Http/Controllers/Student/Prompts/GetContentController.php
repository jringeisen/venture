<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Models\Prompt;
use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Http\Controllers\Controller;

class GetContentController extends Controller
{
    public function __invoke(Request $request, OpenAIService $openAIService)
    {
        $content = str_replace('[AGE]', $request->user()->age, Prompt::where('category', 'tone')->first()->prompt);
        $content = str_replace('[GRADE]', $request->user()->grade, $content);

        $response = $openAIService
            ->messages('system', $content)
            ->messages('user', $request->question)
            ->user($request->user())
            ->create();

        $question = $request->user()->promptQuestions()->latest()->first();

        $question->promptAnswer()->updateOrCreate([
            'prompt_question_id' => $question->id,
        ], [
            'content' => $response['content'],
        ]);

        return response()->json($response);
    }
}
