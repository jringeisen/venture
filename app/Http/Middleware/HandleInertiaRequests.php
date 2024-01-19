<?php

namespace App\Http\Middleware;

use App\Services\StudentInertiaRequests;
use App\Services\UserInertiaRequests;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $values = fn () => [];

        if ($request->user() && is_null($request->user()?->parent_id)) {
            $values = new UserInertiaRequests();
        }

        if ($request->user() && ! is_null($request->user()?->parent_id)) {
            $values = new StudentInertiaRequests();
        }

        return [
            ...parent::share($request),
            ...$values($request),
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ];
    }
}
