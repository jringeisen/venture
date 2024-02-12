<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Contracts\ImpersonatesUsers;

class StartImpersonationController extends Controller
{
    public function __invoke(Request $request, User $user, ImpersonatesUsers $impersonator)
    {
        $this->authorize('view', $user);

        $impersonator->impersonate($request, Auth::guard(), $user);

        return redirect(route('student.prompts.index'));
    }
}
