<?php

namespace App\Http\Controllers;

use App\Enums\ComplianceState;
use App\Http\Requests\GenerateComplianceReportRequest;
use App\Models\ActiveTime;
use App\Models\ComplianceReport;
use App\Services\CompliancePdfService;
use App\Services\ComplianceReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ComplianceController extends Controller
{
    public function __construct(
        private readonly ComplianceReportService $reportService,
        private readonly CompliancePdfService $pdfService,
    ) {}

    public function index(Request $request): Response
    {
        $parent = $request->user();
        $students = $parent->students()->with(['userCourses.course', 'activeTime'])->get();

        $studentData = $students->map(function ($student) {
            $yearStart = now()->startOfYear();
            $yearEnd = now();

            $instructionDays = ActiveTime::where('user_id', $student->id)
                ->whereBetween('date', [$yearStart->toDateString(), $yearEnd->toDateString()])
                ->count();

            $totalSeconds = ActiveTime::where('user_id', $student->id)
                ->whereBetween('date', [$yearStart->toDateString(), $yearEnd->toDateString()])
                ->sum('total_seconds');

            $activeCourses = $student->userCourses->whereNull('completed_at')->count();
            $completedCourses = $student->userCourses->whereNotNull('completed_at')->count();

            return [
                'id' => $student->id,
                'name' => $student->name,
                'grade' => $student->grade,
                'age' => $student->age,
                'instruction_days' => $instructionDays,
                'instruction_hours' => round($totalSeconds / 3600, 1),
                'courses_active' => $activeCourses,
                'courses_completed' => $completedCourses,
            ];
        });

        $reports = $parent->complianceReports()
            ->with('student:id,name')
            ->latest()
            ->paginate(15)
            ->through(function (ComplianceReport $report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'student_name' => $report->student->name,
                    'state' => $report->state->label(),
                    'period_start' => $report->period_start->toFormattedDateString(),
                    'period_end' => $report->period_end->toFormattedDateString(),
                    'status' => ucwords($report->status->value),
                    'generated_at' => $report->generated_at->toFormattedDateString(),
                ];
            });

        return Inertia::render('Teachers/Compliance/Index', [
            'students' => $studentData,
            'reports' => $reports,
        ]);
    }

    public function create(Request $request): Response
    {
        $students = $request->user()->students()->get(['id', 'name', 'grade']);

        return Inertia::render('Teachers/Compliance/Create', [
            'students' => $students,
            'states' => ComplianceState::toSelectArray(),
        ]);
    }

    public function store(GenerateComplianceReportRequest $request): RedirectResponse
    {
        $student = $request->user()->students()->findOrFail($request->validated('student_id'));
        $start = now()->subYears(2)->startOfDay();
        $end = now()->endOfDay();
        $state = ComplianceState::from($request->validated('state'));
        $title = $request->validated('title');

        $report = $this->reportService->generateReport($student, $start, $end, $state, $title);

        return to_route('parent.compliance.reports.show', $report);
    }

    public function show(ComplianceReport $complianceReport): Response
    {
        $this->authorize('view', $complianceReport);

        $complianceReport->load(['student:id,name,grade,age', 'parent:id,name,email']);

        return Inertia::render('Teachers/Compliance/Show', [
            'report' => [
                'id' => $complianceReport->id,
                'title' => $complianceReport->title,
                'state' => $complianceReport->state->label(),
                'status' => ucwords($complianceReport->status->value),
                'period_start' => $complianceReport->period_start->toFormattedDateString(),
                'period_end' => $complianceReport->period_end->toFormattedDateString(),
                'generated_at' => $complianceReport->generated_at->toFormattedDateString(),
                'student' => [
                    'name' => $complianceReport->student->name,
                    'grade' => $complianceReport->student->grade,
                    'age' => $complianceReport->student->age,
                ],
                'parent' => [
                    'name' => $complianceReport->parent->name,
                    'email' => $complianceReport->parent->email,
                ],
                'summary' => $complianceReport->summary_statistics,
                'metadata' => $complianceReport->report_metadata ?? [],
            ],
        ]);
    }

    public function downloadPdf(ComplianceReport $complianceReport): \Symfony\Component\HttpFoundation\Response
    {
        $this->authorize('view', $complianceReport);

        return $this->pdfService->download($complianceReport);
    }

    public function destroy(ComplianceReport $complianceReport): RedirectResponse
    {
        $this->authorize('delete', $complianceReport);

        $complianceReport->delete();

        return to_route('parent.compliance.index');
    }
}
