<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ImpersonationService;
use Illuminate\Http\Request;

class ImpersonationController extends Controller
{
    public function __construct(
        private ImpersonationService $impersonationService
    ) {}

    public function start(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $this->impersonationService->impersonate($request, $user);

        return redirect(route('student.prompts.index'));
    }

    public function stop(Request $request)
    {
        $this->impersonationService->stopImpersonating($request);

        return redirect('/');
    }
}
