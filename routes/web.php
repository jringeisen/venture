<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;

// Guest Routes...
Route::get('/', LandingController::class);

// Authenticated Routes...
Route::middleware('auth')->group(function () {
    // Verified Routes...
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::prefix('students')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('students.index');
            Route::get('/create', [StudentController::class, 'create'])->name('students.create');
            Route::post('/', [StudentController::class, 'store'])->name('students.store');
            Route::get('/{student}', [StudentController::class, 'show'])->name('students.show');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        });
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
