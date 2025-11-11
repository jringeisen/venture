<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use App\Services\OpenAI\OpenAIChatService;
use Illuminate\Http\Request;

class GetQuestionsController extends Controller
{
    public function __invoke(Request $request, OpenAIChatService $chatService)
    {
        $question = $request->user()->promptQuestions()->latest()->first();

        if ($question) {
            $response = $chatService
                ->setUser($request->user())
                ->addMessage('system', Prompt::where('category', 'questions')->first()->prompt)
                ->addMessage('user', $request->question)
                ->updateQuestionTokens($question)
                ->createChat();

            $question->promptAnswer()
                ->updateOrCreate(
                    ['prompt_question_id' => $question->id],
                    ['questions' => $response->questions ?? null]
                );

            return response()->json($response);
        }

        return response()->json(['questions' => []]);
    }
}
