<?php

use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::patch('/students/{student}', [StudentController::class, 'update'])->name('students.update');
