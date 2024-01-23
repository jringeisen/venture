<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function update(Request $request, Student $student): RedirectResponse
    {
        $student->update($request->only('motivational_message'));

        return to_route($request->redirect_route);
    }
}
