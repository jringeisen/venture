<?php

use App\Http\Controllers\Billing\BillingPortalController;
use App\Http\Controllers\Billing\QuantityExceededController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Guest\DownloadPlannerController;
use App\Http\Controllers\Guest\NewsletterController;
use App\Http\Controllers\Guest\PlannerController;
use App\Http\Controllers\Guest\TermsOfServiceController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\StripeCheckoutOptionsController;
use App\Http\Controllers\Student\PromptController;
use App\Http\Controllers\Student\Prompts\GetContentController;
use App\Http\Controllers\Student\Prompts\GetQuestionsController;
use App\Http\Controllers\Student\Prompts\GetSubjectController;
use App\Http\Controllers\Student\StudentActivityController;
use App\Http\Controllers\Student\TopicController;
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
        Route::get('/billing-portal', BillingPortalController::class)->name('billing.portal');
        Route::get('/quantity-exceeded', QuantityExceededController::class)->name('quantity.exceeded');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
        Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
        Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
        Route::patch('/feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
        Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');

        Route::middleware('parent')->prefix('parent')->name('parent.')->group(function () {
            Route::get('/dashboard', DashboardController::class)->name('dashboard');

            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [StudentController::class, 'index'])->name('index');
                Route::get('/create', [StudentController::class, 'create'])->name('create');
                Route::post('/', [StudentController::class, 'store'])->name('store');
                Route::get('/{user}', [StudentController::class, 'show'])->name('show');
                Route::get('/{user}/edit', [StudentController::class, 'edit'])->name('edit');
                Route::patch('/{user}', [StudentController::class, 'update'])->name('update');
                Route::delete('/{user}', [StudentController::class, 'destroy'])->name('destroy');
            });
        });

        Route::middleware('student')->prefix('student')->name('student.')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/prompts', [PromptController::class, 'index'])->name('prompts.index');
            Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');
            Route::post('/prompts/subject', GetSubjectController::class)->name('prompts.subject');
            Route::get('/prompts/content', GetContentController::class)->name('prompts.content');
            Route::post('/prompts/questions', GetQuestionsController::class)->name('prompts.questions');

            Route::get('/topic/{topic}', [TopicController::class, 'show'])->name('topic.show');

            Route::patch('/users/{user}', [StudentController::class, 'update'])->name('users.update');

            Route::post('/activity/update', [StudentActivityController::class, 'update'])->name('activity.update');
            Route::post('/activity/persist', [StudentActivityController::class, 'store'])->name('activity.store');
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
