<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function update(Request $request, Student $student): Student
    {
        return tap($student)->update($request->all());
    }
}
