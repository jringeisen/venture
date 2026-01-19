<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentInertiaRequests
{
    public function __invoke(Request $request): array
    {
        $impersonationService = app(ImpersonationService::class);

        return [
            'auth' => [
                'type' => 'student',
                'user' => $request->user(),
                'navigation' => $this->navigation(),
                'subjects' => $this->subjects(),
                'isImpersonated' => $impersonationService->isImpersonating($request),
            ],
        ];
    }

    protected function navigation(): array
    {
        return [
            ['name' => 'Dashboard', 'href' => '/student/dashboard', 'icon' => 'home-icon', 'current' => request()->routeIs('student.dashboard')],
            ['name' => 'Learn', 'href' => '/student/prompts', 'icon' => 'book-open', 'current' => request()->routeIs('student.prompts.*')],
            ['name' => 'Courses', 'href' => '/student/courses', 'icon' => 'academic-cap', 'current' => request()->routeIs('student.courses.*')],
            ['name' => 'Feedback', 'href' => route('feedback.index'), 'icon' => 'chat-bubble-left-ellipsis', 'current' => request()->routeIs('feedback.*')],
        ];
    }

    protected function subjects(): array
    {
        $subjects = app(StudentService::class)->student(request()->user())->categoriesWithCounts();

        return collect($subjects)->map(function (int $count, string $subject) {
            return [
                'name' => ucwords($subject),
                'href' => '/student/topic/'.Str::slug($subject),
                'count' => $count,
                'current' => url()->current() === route('student.topic.show', Str::slug($subject)),
            ];
        })->toArray();
    }
}
