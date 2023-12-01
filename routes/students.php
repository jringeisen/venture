<?php

use App\Http\Controllers\Student\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Student\Auth\PasswordResetController;
use App\Http\Controllers\Student\PromptController;
use App\Http\Controllers\Student\Prompts\GetContentController;
use App\Http\Controllers\Student\Prompts\GetQuestionsController;
use App\Http\Controllers\Student\Prompts\GetSubjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')
    ->name('student.')
    ->group(static function () {
        Route::middleware('guest:student')->group(static function () {
            Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
            Route::post('login', [AuthenticatedSessionController::class, 'store']);
        });

        Route::middleware('auth:student')->group(static function () {
            Route::middleware('temporary.password')->group(static function () {
                Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');

                Route::get('/prompts', [PromptController::class, 'index'])->name('prompts.index');
                Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');
                Route::post('/prompts/subject', GetSubjectController::class)->name('prompts.subject');
                Route::get('/prompts/content', GetContentController::class)->name('prompts.content');
                Route::post('/prompts/questions', GetQuestionsController::class)->name('prompts.questions');

                Route::get('/topic/{topic}', [\App\Http\Controllers\Student\TopicController::class, 'show'])->name('topic.show');
            });

            Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

            Route::controller(PasswordResetController::class)
                ->prefix('password-reset')
                ->name('password-reset.')
                ->group(function () {
                    Route::get('/', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                });
        });
    });
