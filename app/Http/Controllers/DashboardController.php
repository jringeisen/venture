<?php

namespace App\Http\Controllers;

use App\Services\ParentDashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(private readonly ParentDashboardService $dashboardService) {}

    public function __invoke(Request $request)
    {
        $parent = $request->user();

        return Inertia::render('Teachers/Dashboard', [
            'familyStats' => $this->dashboardService->getFamilyStats($parent),
            'studentCards' => $this->dashboardService->getStudentCards($parent),
            'recentActivity' => $this->dashboardService->getRecentActivity($parent),
        ]);
    }
}
