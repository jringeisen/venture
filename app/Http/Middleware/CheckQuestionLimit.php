<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CheckQuestionLimit
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {}

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->subscriptionService->canAskQuestion($request->user())) {
            return Inertia::render('Student/Prompts/Index', [
                'result' => [
                    'flagged' => true,
                    'message' => 'You have reached your daily question limit. Upgrade your plan for more questions.',
                    'upgrade_feature' => 'ai_questions',
                ],
            ])->toResponse($request);
        }

        return $next($request);
    }
}
