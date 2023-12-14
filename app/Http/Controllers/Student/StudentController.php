<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller
{
    public function update(Request $request, Student $student): RedirectResponse
    {
        $student->update($request->only('motivational_message'));

        return to_route($request->redirect_route);
    }
}
