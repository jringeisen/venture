<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use App\Services\Interfaces\AIServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class GetContentController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): StreamedResponse
    {
        $aIService = app()->make(AIServiceInterface::class, ['aiService' => 'OpenAI']);

        $usersAge = $request->user()->age;

        $prompt = Prompt::where('category', 'like', "%$usersAge%")->first()->prompt;

        $question = $request->user()->promptQuestions()->latest()->first();

        throw_unless($question, "No prompt question exists for the given user: {$request->user()->id}");

        $aIService
            ->addMessage(
                'system',
                $prompt
            )
            ->addMessage(
                'user',
                $question->question
            );

        $aIService->updateQuestionTokens($question);

        return $aIService->createStream()->stream;
    }
}
