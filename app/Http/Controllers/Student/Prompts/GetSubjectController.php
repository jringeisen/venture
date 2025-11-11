<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use App\Services\OpenAI\OpenAIChatService;
use Illuminate\Http\Request;

class GetSubjectController extends Controller
{
    public function __invoke(Request $request, OpenAIChatService $chatService)
    {
        $question = $request->user()->promptQuestions()->latest()->first();

        $response = $chatService
            ->setUser($request->user())
            ->addMessage('system', Prompt::where('category', 'categorize')->first()->prompt)
            ->addMessage('user', $request->question)
            ->updateQuestionTokens($question)
            ->createChat();

        $question->promptAnswer()
            ->updateOrCreate(
                ['prompt_question_id' => $question->id],
                ['subject_category' => strtolower($response->subject)]
            );

        return response()->json($response);
    }
}
