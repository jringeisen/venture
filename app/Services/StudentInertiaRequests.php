<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tightenco\Ziggy\Ziggy;

class StudentInertiaRequests
{
    public function __invoke(Request $request)
    {
        return [
            'auth' => [
                'type' => 'student',
                'user' => $request->user(),
                'navigation' => $this->navigation(),
                'subjects' => $this->subjects(),
                'motivationalMessage' => $request->routeIs('student.dashboard')
                    ? (new MotivationalMessageService($request->user()))->generate()
                    : null,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ];
    }

    protected function navigation(): array
    {
        return [
            ['name' => 'Dashboard', 'href' => '/student/dashboard', 'icon' => 'home-icon', 'current' => request()->routeIs('student.dashboard')],
            ['name' => 'Learn', 'href' => '/student/prompts', 'icon' => 'book-open', 'current' => request()->routeIs('student.prompts.*')],
        ];
    }

    protected function subjects(): array
    {
        $subjects = (new StudentService)->student(request()->user())->categoriesWithCounts();

        return collect($subjects)->map(function ($count, $subject) {
            return [
                'name' => ucwords($subject),
                'href' => '/student/topic/'.Str::slug($subject),
                'count' => $count,
                'current' => url()->current() === route('student.topic.show', Str::slug($subject)),
            ];
        })->toArray();
    }
}
