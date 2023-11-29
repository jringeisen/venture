<?php

namespace App\Services;

use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;

class StudentInertiaRequests {

    public function __invoke(Request $request) {
        return [
            'auth' => [
                'type' => 'student',
                'user' => $request->user(),
                'navigation' => $this->navigation(),
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
            ['name' => 'Prompts', 'href' => '/student/prompts', 'icon' => 'document-icon', 'current' => request()->routeIs('student.prompts.*')],
        ];
    }
}
