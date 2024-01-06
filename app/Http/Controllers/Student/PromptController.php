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
            'canAskQuestions' => $request->user()->canAskQuestions(),
        ]);
    }

    public function store(PromptRequest $request): Response
    {
        $request->user()->promptQuestions()->create([
            'question' => $request->question,
        ]);

        $request->user()->user->increment('total_questions_asked');

        return Inertia::render('Student/Prompts/Index', [
            'canAskQuestions' => $request->user()->canAskQuestions(),
            'result' => [
                'flagged' => false,
                'message' => '',
            ],
        ]);
    }
}
