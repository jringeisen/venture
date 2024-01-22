<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Feedback/Index', [
            'feedback' => $request->user()
                ->feedback()
                ->latest()
                ->paginate(15)
                ->through(function ($feedback) {
                    return [
                        'id' => $feedback->id,
                        'title' => $feedback->title,
                        'description' => $feedback->description,
                        'status' => ucwords($feedback->status->value),
                        'created_at' => $feedback->created_at->toFormattedDateString(),
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Feedback/Create');
    }

    public function store(FeedbackRequest $request)
    {
        $request->user()->feedback()->create($request->validated());

        return to_route('feedback.index');
    }

    public function show()
    {
        //
    }

    public function edit(Feedback $feedback): Response
    {
        return Inertia::render('Feedback/Edit', [
            'feedback' => $feedback,
        ]);
    }

    public function update(FeedbackRequest $request, Feedback $feedback)
    {
        $feedback->update($request->validated());

        return to_route('feedback.index');
    }

    public function destroy()
    {
        //
    }
}
