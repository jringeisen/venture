<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FeedbackStatuses;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FeedbackController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Feedback::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        return Inertia::render('Admin/Feedback/Index', [
            'feedback' => $query->latest()->paginate(10)->withQueryString(),
            'statuses' => FeedbackStatuses::toSelectArray(),
            'filters' => $request->only(['status']),
        ]);
    }

    public function show(Feedback $feedback): Response
    {
        return Inertia::render('Admin/Feedback/Show', [
            'feedback' => $feedback->load('user'),
            'statuses' => FeedbackStatuses::toSelectArray(),
        ]);
    }

    public function update(Request $request, Feedback $feedback): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:'.implode(',', array_column(FeedbackStatuses::cases(), 'value')),
        ]);

        $feedback->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Feedback status updated successfully.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('success', 'Feedback deleted successfully.');
    }
}
