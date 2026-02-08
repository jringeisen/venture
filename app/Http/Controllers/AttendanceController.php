<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceType;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
    ) {}

    public function index(Request $request): Response
    {
        $parent = $request->user();
        $students = $parent->students()->get(['id', 'name']);
        $selectedStudentId = $request->get('student_id', $students->first()?->id);
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        $attendance = [];
        $summary = null;
        $yearSummary = null;
        $schoolYearRange = $parent->getSchoolYearRange();
        $schoolYearLabel = $schoolYearRange['start']->format('M j, Y').' - '.$schoolYearRange['end']->format('M j, Y');

        if ($selectedStudentId) {
            $student = $parent->students()->findOrFail($selectedStudentId);
            $attendance = $this->attendanceService->getMonthlyAttendance($student, $year, $month);

            $monthStart = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $summary = $this->attendanceService->getAttendanceSummary($student, $monthStart, $monthEnd);
            $yearSummary = $this->attendanceService->getAttendanceSummary($student, $schoolYearRange['start'], $schoolYearRange['end']);
        }

        return Inertia::render('Teachers/Attendance/Index', [
            'students' => $students,
            'selectedStudentId' => (int) $selectedStudentId,
            'year' => $year,
            'month' => $month,
            'attendance' => $attendance,
            'summary' => $summary,
            'yearSummary' => $yearSummary,
            'schoolYearLabel' => $schoolYearLabel,
            'attendanceTypes' => AttendanceType::toSelectArray(),
        ]);
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Attendance::updateOrCreate(
            [
                'user_id' => $validated['student_id'],
                'date' => $validated['date'],
            ],
            [
                'type' => $validated['type'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => $request->user()->id,
            ]
        );

        return back();
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $attendance->update([
            'type' => $request->validated('type'),
            'notes' => $request->validated('notes'),
        ]);

        return back();
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $this->authorize('delete', $attendance);

        $attendance->delete();

        return back();
    }

    public function dailySummary(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        $student = $request->user()->students()->findOrFail($request->get('student_id'));

        return response()->json(
            $this->attendanceService->getDailyWorkOverview($student, $request->get('date'))
        );
    }
}
