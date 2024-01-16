<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\Timezone;
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
    private StudentService $studentService;

    public function __construct()
    {
        $this->studentService = new StudentService;
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
            'password' => Hash::make($data['password']),
        ]);

        return to_route('students.index');
    }

    public function edit(Student $student): Response
    {
        $this->authorize('update', $student);

        return Inertia::render('Teachers/Students/Edit', [
            'student' => $student->only('id', 'name', 'username', 'grade', 'age', 'timezone'),
            'timezones' => Timezone::orderBy('value', 'asc')->get(),
        ]);
    }

    public function show(Request $request, Student $student): Response
    {
        $this->authorize('view', $student);

        return Inertia::render('Teachers/Students/Show', [
            'student' => (new StudentResource($student->load('promptQuestions')))->resolve(),
            'totalQuestions' => $this->studentService->student($student)->totalQuestionsAsked(),
            'dailyQuestions' => $this->studentService->student($student)->totalQuestionsAskedToday(),
            'totalWordsRead' => (new WordCountService)->calculateWordsForPromptAnswers($student),
            'categoriesWithCounts' => $this->studentService->student($student)->categoriesWithCounts(),
            'pieChartData' => $this->studentService->student($student)->pieChartData(),
        ]);
    }

    public function update(StudentUpdateRequest $request, Student $student): RedirectResponse
    {
        $this->authorize('update', $student);

        $data = $request->validated();

        if ($data['password'] === null) {
            $student->update($request->only('name', 'username', 'grade', 'age', 'timezone'));
        } else {
            $student->update([
                ...$data,
                'password' => Hash::make($data['password']),
            ]);
        }

        return to_route('students.index');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->authorize('update', $student);

        $student->delete();

        return to_route('students.index');
    }
}
