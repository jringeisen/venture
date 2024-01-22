<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAttendanceRequest;
use App\Services\StudentAttendanceService;
use JsonException;

class StudentActivityController extends Controller
{
    public function __construct(private readonly StudentAttendanceService $attendanceService)
    {
    }

    /**
     * @throws JsonException
     */
    public function update(StudentAttendanceRequest $request): array
    {
        $data = $request->validated();

        $this->attendanceService->update($request->user(), $data['totalSeconds']);

        return ['message' => 'Okay'];
    }

    public function store(StudentAttendanceRequest $request): array
    {
        $data = $request->validated();

        $this->attendanceService->persist($request->user(), $data['totalSeconds']);

        return ['message' => 'Okay'];
    }
}
