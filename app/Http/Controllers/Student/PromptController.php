<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromptRequest;
use App\Models\Prompt;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use OpenAI\Laravel\Facades\OpenAI;

class PromptController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Student/Prompts/Index', [
            'canAskQuestions' => $request->user()->canAskQuestions(),
        ]);
    }

    public function store(PromptRequest $request, OpenAIService $openAIService): Response
    {
        $question = $request->user()->promptQuestions()->create([
            'question' => $request->question,
        ]);

        $request->user()->user->increment('total_questions_asked');

        $moderation = OpenAI::moderations()->create([
            'model' => 'text-moderation-latest',
            'input' => $request->question,
        ]);

        if ($moderation->results[0]->flagged === true) {
            return Inertia::render('Student/Prompts/Index', [
                'canAskQuestions' => $request->user()->canAskQuestions(),
                'result' => [
                    'flagged' => true,
                    'message' => 'This question violates OpenAI\'s policies. Please try another question.',
                ],
            ]);
        }

        $response = $openAIService
            ->messages('system', Prompt::where('category', 'moderation')->first()->prompt)
            ->messages('user', $request->question)
            ->user($request->user())
            ->updateQuestionTokens($question)
            ->create();

        if (isset($response['flagged']) && $response['flagged'] === true) {
            return Inertia::render('Student/Prompts/Index', [
                'canAskQuestions' => $request->user()->canAskQuestions(),
                'result' => $response,
            ]);
        }

        return Inertia::render('Student/Prompts/Index', [
            'canAskQuestions' => $request->user()->canAskQuestions(),
            'result' => [
                'flagged' => false,
                'message' => '',
            ],
        ]);
    }
}
