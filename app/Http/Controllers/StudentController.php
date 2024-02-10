<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\StudentResource;
use App\Models\Timezone;
use App\Models\User;
use App\Services\StudentService;
use App\Services\WordCountService;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    private StudentService $studentService;

    public function __construct(
        private readonly WordCountService $wordCountService
    ) {
        $this->studentService = app(StudentService::class);
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Teachers/Students/Index', [
            'students' => $request->user()
                ->students()
                ->withCount(['promptQuestions' => function (Builder $query) {
                    $query->whereHas('promptAnswer');
                }])
                ->with(
                    'activeTime',
                    fn (Builder $query) => $query->where(
                        'date',
                        Carbon::now()
                            ->setTimezone($request->user()->timezone)
                            ->format('Y-m-d')
                    )
                )
                ->paginate(10),
            'showInitialPaymentPage' => $request->user()->showInitialPaymentPage(),
            'showExceededQuantityPage' => $request->user()->showExceededQuantityPage(),
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

    public function create(): Response
    {
        return Inertia::render('Teachers/Students/Create', [
            'timezones' => Timezone::orderBy('value', 'asc')->get(),
        ]);
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

        $timeframe = $request->get('timeframe', 'yearly');

        return Inertia::render('Teachers/Students/Show', [
            'student' => (new StudentResource($user->load('promptQuestions')))->resolve(),
            'totalQuestions' => $this->studentService->student($user)->totalQuestionsAsked($timeframe),
            'dailyQuestions' => $this->studentService->student($user)->totalQuestionsAskedToday(),
            'totalWordsRead' => $this->wordCountService->calculateTotalWordsRead($user, $timeframe),
            'categoriesWithCounts' => $this->studentService->student($user)->categoriesWithCounts(),
            'lineChartData' => $this->studentService->student($user)->lineChartData($timeframe),
            'activeTime' => $this->studentService->student($user)->activeTime($timeframe),
            'timeframe' => $timeframe,
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return to_route('parent.users.index');
    }

    public function update(StudentUpdateRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

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
}
