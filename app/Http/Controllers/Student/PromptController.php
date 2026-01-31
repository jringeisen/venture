<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromptRequest;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PromptController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('Student/Prompts/Index');
    }

    public function store(PromptRequest $request): Response
    {
        $request->user()->promptQuestions()->create([
            'question' => $request->question,
        ]);

        $request->user()->parent->increment('total_questions_asked');
        $this->subscriptionService->recordQuestion($request->user());

        return Inertia::render('Student/Prompts/Index', [
            'result' => [
                'flagged' => false,
                'message' => '',
            ],
        ]);
    }
}
