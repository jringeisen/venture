<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Contracts\ImpersonatesUsers;

class ImpersonationController extends Controller
{
    public function start(Request $request, User $user, ImpersonatesUsers $impersonator)
    {
        $this->authorize('view', $user);

        $impersonator->impersonate($request, Auth::guard(), $user);

        return redirect(route('student.prompts.index'));
    }

    public function stop(Request $request, ImpersonatesUsers $impersonator)
    {
        $impersonator->stopImpersonating($request, Auth::guard(), User::class);

        return redirect('/');
    }
}
