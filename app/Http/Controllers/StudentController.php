<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\StudentResource;
use App\Models\Timezone;
use App\Models\User;
use App\Services\StudentService;
use App\Services\WordCountService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function __construct(
        private readonly StudentService $studentService,
        private readonly WordCountService $wordCountService,
    ) {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Teachers/Students/Index', [
            'students' => $request->user()->students()->withCount(['promptQuestions' => function (Builder $query) {
                $query->whereHas('promptAnswer');
            }])
                ->paginate(10),
            'showInitialPaymentPage' => $request->user()->showInitialPaymentPage(),
            'showExceededQuantityPage' => $request->user()->showExceededQuantityPage(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Teachers/Students/Create', [
            'timezones' => Timezone::orderBy('value', 'asc')->get(),
        ]);
    }

    public function store(StudentStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $request->user()->students()->create([
            ...$data,
            'email_verified_at' => now(),
            'password' => Hash::make($data['password']),
        ]);

        return to_route('parent.users.index');
    }

    public function edit(User $user): Response
    {
        $this->authorize('update', $user);

        return Inertia::render('Teachers/Students/Edit', [
            'student' => $user->only('id', 'name', 'username', 'grade', 'age', 'timezone'),
            'timezones' => Timezone::orderBy('value', 'asc')->get(),
        ]);
    }

    public function show(Request $request, User $user): Response
    {
        $this->authorize('view', $user);

        return Inertia::render('Teachers/Students/Show', [
            'student' => (new StudentResource($user->load('promptQuestions')))->resolve(),
            'totalQuestions' => $this->studentService->student($user)->totalQuestionsAsked(),
            'dailyQuestions' => $this->studentService->student($user)->totalQuestionsAskedToday(),
            'totalWordsRead' => $this->wordCountService->calculateWordsForPromptAnswers($user),
            'categoriesWithCounts' => $this->studentService->student($user)->categoriesWithCounts(),
            'pieChartData' => $this->studentService->student($user)->pieChartData(),
        ]);
    }

    public function update(StudentUpdateRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (! isset($data['password'])) {
            $user->update($request->only('name', 'username', 'grade', 'age', 'timezone', 'motivational_message'));
        } else {
            $user->update([
                ...$data,
                'password' => Hash::make($data['password']),
            ]);
        }

        return to_route('parent.users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $user->delete();

        return to_route('parent.users.index');
    }
}
