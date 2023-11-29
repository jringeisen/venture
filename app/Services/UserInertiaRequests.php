<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tightenco\Ziggy\Ziggy;

class UserInertiaRequests
{
    public function __invoke(Request $request)
    {
        return [
            'auth' => [
                'type' => 'teacher',
                'user' => $request->user(),
                'students' => $this->students(),
                'navigation' => $this->navigation(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'message' => [
                    'id' => fn () => $request->session()->get('message_id'),
                    'status' => fn () => $request->session()->get('status'),
                    'title' => fn () => $request->session()->get('title'),
                    'body' => fn () => $request->session()->get('body'),
                ],
            ],
        ];
    }

    protected function students(): ?array
    {
        if (! request()->user()?->students) {
            return null;
        }

        return request()->user()?->students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'initial' => strtoupper(Str::substr($student->name, 0, 1)),
                'href' => '/students/'.$student->id,
                'icon' => folderSvgIcon(),
            ];
        })->toArray();
    }

    protected function navigation(): array
    {
        return [
            ['name' => 'Dashboard', 'href' => '/dashboard', 'icon' => 'home-icon', 'current' => request()->routeIs('dashboard')],
            ['name' => 'Students', 'href' => '/students', 'icon' => 'users-icon', 'current' => request()->routeIs('students.*')],
        ];
    }
}
