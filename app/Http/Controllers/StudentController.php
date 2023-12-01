<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Resources\StudentResource;
use App\Mail\TemporaryPasswordEmail;
use App\Models\Student;
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
    public function index(Request $request): Response
    {
        return Inertia::render('Teachers/Students/Index', [
            'students' => $request->user()->students()->withCount(['promptQuestions' => function (Builder $query) {
                $query->whereDate('created_at', today());
            }])->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Teachers/Students/Create');
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

    public function show(Request $request, Student $student): Response
    {
        return Inertia::render('Teachers/Students/Show', [
            'student' => (new StudentResource($student->load('promptQuestions')))->resolve(),
            'date' => $request->date ?? now()->format('Y-m-d'),
        ]);
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return to_route('students.index');
    }
}
