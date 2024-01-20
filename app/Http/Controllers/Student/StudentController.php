<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAttendanceRequest;
use App\Models\Student;
use App\Services\StudentAttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(private readonly StudentAttendanceService $attendanceService)
    {}

    public function update(Request $request, Student $student): RedirectResponse
    {
        $student->update($request->only('motivational_message'));

        return to_route($request->redirect_route);
    }

    public function persistActivity(StudentAttendanceRequest $request): array
    {
        $data = $request->validated();

        $this->attendanceService->persist($data['totalSeconds']);

        return ['message' => 'Okay'];
    }

    public function updateActivity(StudentAttendanceRequest $request): array
    {
        $data = $request->validated();

        $this->attendanceService->update($data['totalSeconds']);

        return ['message' => 'Okay'];
    }
}
