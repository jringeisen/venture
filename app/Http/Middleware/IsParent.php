<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsParent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->isStudent()) {
            return redirect(RouteServiceProvider::STUDENT_HOME);
        }

        if ($request->route()->named('parent.users.create') || $request->route()->named('parent.users.store')) {
            return $next($request);
        }

        if (! $user->students()->count()) {
            return redirect()->route('parent.users.create', ['status' => 'onboarding']);
        }

        return $next($request);
    }
}
