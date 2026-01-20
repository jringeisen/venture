<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Course;
use App\Models\Feedback;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'users' => User::count(),
                'courses' => Course::count(),
                'blogPosts' => BlogPost::count(),
                'feedback' => Feedback::count(),
            ],
            'recentFeedback' => Feedback::with('user')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
