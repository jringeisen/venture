<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Instanceof_;
use App\Services\UserInertiaRequests;
use App\Services\StudentInertiaRequests;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        if ($request->user() instanceof \App\Models\User) {
            $values = new UserInertiaRequests();
        }

        if ($request->user() instanceof \App\Models\Student) {
            $values = new StudentInertiaRequests();
        }

        return array_merge(parent::share($request), isset($values) ? $values($request) : []);
    }
}
