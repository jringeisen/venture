<?php

use App\Http\Controllers\Billing\BillingPortalController;
use App\Http\Controllers\Billing\QuantityExceededController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Guest\DownloadPlannerController;
use App\Http\Controllers\Guest\NewsletterController;
use App\Http\Controllers\Guest\PlannerController;
use App\Http\Controllers\Guest\TermsOfServiceController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\StripeCheckoutOptionsController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Guest Routes...
Route::middleware('guest')->group(static function () {
    Route::get('/', LandingController::class)->name('landing');
    Route::get('/planner', PlannerController::class)->name('planner');

    Route::post('/newsletter-lists', [NewsletterController::class, 'subscribe'])->name('newsletter-list.subscribe');
    Route::get('/newsletter-lists/{newsletter_list:email}', [NewsletterController::class, 'unsubscribe'])
        ->name('newsletter-list.unsubscribe')
        ->middleware('signed');

    Route::get('privacy-policy', TermsOfServiceController::class)->name('privacy-policy');
    Route::get('terms-of-service', TermsOfServiceController::class)->name('terms-of-service');

    Route::get('/student-planner/download', DownloadPlannerController::class)->name('student-planner.download');
});

// Authenticated Routes...
Route::middleware('auth')->group(function () {
    // Verified Routes...
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::get('/billing-portal', BillingPortalController::class)->name('billing.portal');
        Route::get('/quantity-exceeded', QuantityExceededController::class)->name('quantity.exceeded');

        Route::prefix('users')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('students.index');
            Route::get('/create', [StudentController::class, 'create'])->name('students.create');
            Route::post('/', [StudentController::class, 'store'])->name('students.store');
            Route::get('/{user}', [StudentController::class, 'show'])->name('students.show');
            Route::get('/{user}/edit', [StudentController::class, 'edit'])->name('students.edit');
            Route::patch('/{user}', [StudentController::class, 'update'])->name('students.update');
            Route::delete('/{user}', [StudentController::class, 'destroy'])->name('students.destroy');
        });

        Route::get('/student/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');

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
