<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromptRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PromptController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Student/Prompts/Index', [
            'linkClicked' => (bool) $request->linkClicked,
            'canAskQuestions' => $request->user()->canAskQuestions(),
            'questions' => $request->questions,
        ]);
    }

    public function store(PromptRequest $request): Response
    {
        $request->user()->promptQuestions()->create([
            'question' => $request->question,
        ]);

        $request->user()->parent->increment('total_questions_asked');

        return Inertia::render('Student/Prompts/Index', [
            'canAskQuestions' => $request->user()->canAskQuestions(),
            'linkClicked' => (bool) $request->linkClicked,
            'questions' => $request->questions,
            'result' => [
                'flagged' => false,
                'message' => '',
            ],
        ]);
    }
}
