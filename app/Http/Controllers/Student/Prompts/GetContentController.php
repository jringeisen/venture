<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use App\Services\OpenAI\OpenAIChatService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class GetContentController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request, OpenAIChatService $chatService): StreamedResponse
    {
        $usersAge = $request->user()->age;

        $prompt = Prompt::where('category', 'like', "%$usersAge%")->first()->prompt;

        $question = $request->user()->promptQuestions()->latest()->first();

        throw_unless($question, "No prompt question exists for the given user: {$request->user()->id}");

        $chatService
            ->setUser($request->user())
            ->addMessage('system', $prompt)
            ->addMessage('user', $question->question)
            ->updateQuestionTokens($question);

        return $chatService->createStream()->stream;
    }
}
