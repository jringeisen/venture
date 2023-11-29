<?php

namespace App\Http\Controllers\Student\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function create()
    {
        return Inertia::render('Student/PasswordReset');
    }

    public function store(Request $request)
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
