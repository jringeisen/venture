<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\StripeCheckoutOptionsController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Guest Routes...
Route::get('/', LandingController::class);

Route::get('/auth-options', function () {
    return \Inertia\Inertia::render('Landing');
});

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
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
            Route::patch('/{student}', [StudentController::class, 'update'])->name('students.update');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        });

        Route::get('/subscription-checkout', StripeCheckoutController::class)->name('subscription.checkout');
        Route::get('/subscription-checkout-options', StripeCheckoutOptionsController::class)->name('subscription.checkout.options');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
