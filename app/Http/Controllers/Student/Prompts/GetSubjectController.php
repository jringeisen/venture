<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use App\Services\Interfaces\AIServiceInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class GetSubjectController extends Controller
{
    /**
     * @throws BindingResolutionException
     */
    public function __invoke(Request $request)
    {
        $service = app()->make(AIServiceInterface::class, ['aiService' => 'OpenAI']);

        $question = $request->user()->promptQuestions()->latest()->first();

        $response = $service
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
