<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Student/PasswordReset');
    }

    public function store(Request $request): string
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
            'is_temporary_password' => false,
        ]);

        return to_route('student.dashboard');
    }
}
