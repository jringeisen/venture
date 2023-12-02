<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;

class PromptController extends Controller
{
    public function index()
    {
        return Inertia::render('Student/Prompts/Index');
    }

    public function store(Request $request, OpenAIService $openAIService)
    {
        $question = $request->user()->promptQuestions()->create([
            'question' => $request->question,
        ]);

        $moderation = OpenAI::moderations()->create([
            'model' => 'text-moderation-latest',
            'input' => $request->question,
        ]);

        if ($moderation->results[0]->flagged === true) {
            return Inertia::render('Student/Prompts/Index', [
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
                'result' => $response,
            ]);
        }

        return Inertia::render('Student/Prompts/Index', [
            'result' => [
                'flagged' => false,
            ],
        ]);
    }
}
