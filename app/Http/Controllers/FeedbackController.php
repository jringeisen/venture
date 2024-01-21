<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;

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

    public function edit()
    {
        //
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
