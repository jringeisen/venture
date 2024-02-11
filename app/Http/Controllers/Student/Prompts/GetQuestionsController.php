<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use App\Services\Dto\AIContentDto;
use App\Services\Interfaces\AIServiceInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class GetQuestionsController extends Controller
{
    /**
     * @throws BindingResolutionException
     */
    public function __invoke(Request $request)
    {
        $service = app()->make(AIServiceInterface::class, ['aiService' => 'OpenAI']);

        $question = $request->user()->promptQuestions()->latest()->first();

        if ($question) {
            $response = $service
                ->addMessage('system', Prompt::where('category', 'questions')->first()->prompt)
                ->addMessage('user', $request->question)
                ->updateQuestionTokens($question)
                ->createChat();

            $question->promptAnswer()
                ->updateOrCreate(
                    ['prompt_question_id' => $question->id],
                    ['questions' => $response->questions ?? null]
                );
        } else {
            $response = new AIContentDto();
        }

        return response()->json($response);
    }
}
