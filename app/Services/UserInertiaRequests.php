<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                'subscription' => fn () => app(SubscriptionService::class)->getSubscriptionSummary($request->user()),
            ],
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

        return request()->user()?->students->map(function (User $student) {
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
            ['name' => 'Dashboard', 'href' => route('parent.dashboard'), 'icon' => 'home-icon', 'current' => request()->routeIs('parent.dashboard')],
            ['name' => 'Students', 'href' => route('parent.users.index'), 'icon' => 'users-icon', 'current' => request()->routeIs('parent.users.*')],
            ['name' => 'Attendance', 'href' => route('parent.attendance.index'), 'icon' => 'calendar-days', 'current' => request()->routeIs('parent.attendance.*')],
            ['name' => 'Compliance', 'href' => route('parent.compliance.index'), 'icon' => 'document-icon', 'current' => request()->routeIs('parent.compliance.*')],
            ['name' => 'Subscription', 'href' => route('parent.subscription.index'), 'icon' => 'credit-card', 'current' => request()->routeIs('parent.subscription.*')],
            ['name' => 'Feedback', 'href' => route('feedback.index'), 'icon' => 'chat-bubble-left-ellipsis', 'current' => request()->routeIs('feedback.*')],
        ];
    }
}
