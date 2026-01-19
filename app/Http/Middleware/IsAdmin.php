<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isLocal = app()->environment('local');
        $isAdmin = $request->user()?->isAdmin();

        if (! $request->user() || (! $isAdmin && ! $isLocal)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
