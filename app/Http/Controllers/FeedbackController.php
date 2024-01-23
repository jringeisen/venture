<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FeedbackController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Feedback/Index', [
            'feedback' => $request->user()
                ->feedback()
                ->latest()
                ->paginate(15)
                ->through(function (Feedback $feedback) {
                    return [
                        'id' => $feedback->id,
                        'title' => $feedback->title,
                        'description' => $feedback->description,
                        'status' => ucwords(str_replace('_', ' ', $feedback->status->value)),
                        'created_at' => $feedback->created_at->toFormattedDateString(),
                    ];
                }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Feedback/Create');
    }

    public function store(FeedbackRequest $request): RedirectResponse
    {
        $request->user()->feedback()->create($request->validated());

        return to_route('feedback.index');
    }

    public function edit(Feedback $feedback): Response
    {
        $this->authorize('update', $feedback);

        return Inertia::render('Feedback/Edit', [
            'feedback' => $feedback,
        ]);
    }

    public function update(FeedbackRequest $request, Feedback $feedback): RedirectResponse
    {
        $this->authorize('update', $feedback);

        $feedback->update($request->validated());

        return to_route('feedback.index');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $this->authorize('delete', $feedback);

        $feedback->delete();

        return to_route('feedback.index');
    }
}
