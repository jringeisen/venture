<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationService
{
    public function impersonate(Request $request, User $user): void
    {
        $request->session()->put('impersonating', [
            'user_id' => Auth::id(),
            'impersonated_id' => $user->id,
        ]);

        Auth::login($user);
    }

    public function stopImpersonating(Request $request): void
    {
        $impersonation = $request->session()->get('impersonating');

        if ($impersonation) {
            $originalUser = User::find($impersonation['user_id']);

            if ($originalUser) {
                Auth::login($originalUser);
            }

            $request->session()->forget('impersonating');
        }
    }

    public function isImpersonating(Request $request): bool
    {
        return $request->session()->has('impersonating');
    }
}
