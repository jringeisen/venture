<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Contracts\ImpersonatesUsers;

class StudentInertiaRequests
{
    public function __invoke(Request $request): array
    {
        $impersonator = app(ImpersonatesUsers::class);

        return [
            'auth' => [
                'type' => 'student',
                'user' => $request->user(),
                'navigation' => $this->navigation(),
                'subjects' => $this->subjects(),
                'isImpersonated' => $impersonator->impersonating($request),
                'motivationalMessage' => $request->routeIs('student.dashboard')
                    ? (new MotivationalMessageService($request->user()))->generate()
                    : null,
            ],
        ];
    }

    protected function navigation(): array
    {
        return [
            ['name' => 'Dashboard', 'href' => '/student/dashboard', 'icon' => 'home-icon', 'current' => request()->routeIs('student.dashboard')],
            ['name' => 'Learn', 'href' => '/student/prompts', 'icon' => 'book-open', 'current' => request()->routeIs('student.prompts.*')],
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
