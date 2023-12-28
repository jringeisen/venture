<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Resources\StudentResource;
use App\Mail\TemporaryPasswordEmail;
use App\Models\Student;
use App\Models\Timezone;
use App\Services\StudentService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
                $query->whereHas('promptAnswer')->filterByDate(today()->toDateString());
            }])->paginate(10),
            'subscribed' => $request->user()->subscribed(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Teachers/Students/Create', [
            'timezones' => Timezone::orderBy('value', 'asc')->get()
        ]);
    }

    public function store(StudentStoreRequest $request): RedirectResponse
    {
        $temporary_password = Str::random(16);

        $student = $request->user()
            ->students()
            ->create([
                ...$request->validated(),
                'password' => Hash::make($temporary_password),
            ]);

        Mail::to($request->email)
            ->send(new TemporaryPasswordEmail($student, $temporary_password));

        return to_route('students.index');
    }

    public function edit(Student $student): Response
    {
        return Inertia::render('Teachers/Students/Edit', [
            'student' => $student->only('id', 'name', 'email', 'grade', 'age', 'timezone'),
            'timezones' => Timezone::orderBy('value', 'asc')->get(),
        ]);
    }

    public function show(Request $request, Student $student): Response
    {
        return Inertia::render('Teachers/Students/Show', [
            'student' => (new StudentResource($student->load('promptQuestions')))->resolve(),
            'totalQuestions' => $this->studentService->student($student)->totalQuestionsAsked(),
            'dailyQuestions' => $this->studentService->student($student)->totalQuestionsAskedToday(),
            'categoriesWithCounts' => $this->studentService->student($student)->categoriesWithCounts(),
            'pieChartData' => $this->studentService->student($student)->pieChartData(),
        ]);
    }

    public function update(StudentStoreRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return to_route('students.index');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return to_route('students.index');
    }
}
