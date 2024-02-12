<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Contracts\ImpersonatesUsers;

class StopImpersonationController extends Controller
{
    public function __invoke(Request $request, ImpersonatesUsers $impersonator)
    {
        $impersonator->stopImpersonating($request, Auth::guard(), User::class);

        return redirect('/');
    }
}
