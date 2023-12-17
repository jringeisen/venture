<?php

namespace App\Services;

use Illuminate\Http\Request;
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
}
