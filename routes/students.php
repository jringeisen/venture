<?php

use App\Http\Controllers\Student\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\PromptController;
use App\Http\Controllers\Student\Prompts\GetContentController;
use App\Http\Controllers\Student\Prompts\GetQuestionsController;
use App\Http\Controllers\Student\Prompts\GetSubjectController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\TopicController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')
    ->name('student.')
    ->group(static function () {
        Route::middleware('guest:student')->group(static function () {
            Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
            Route::post('login', [AuthenticatedSessionController::class, 'store']);
        });

        Route::middleware('auth:student')->group(static function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            Route::get('/prompts', [PromptController::class, 'index'])->name('prompts.index');
            Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');
            Route::post('/prompts/subject', GetSubjectController::class)->name('prompts.subject');
            Route::get('/prompts/content', GetContentController::class)->name('prompts.content');
            Route::post('/prompts/questions', GetQuestionsController::class)->name('prompts.questions');

            Route::get('/topic/{topic}', [TopicController::class, 'show'])->name('topic.show');

            Route::patch('/students/{student}', [StudentController::class, 'update'])->name('students.update');

            Route::post('/activity/update', [StudentController::class, 'updateActivity'])->name('students.activity.update');
            Route::post('/activity/persist', [StudentController::class, 'persistActivity'])->name('students.activity.persist');

            Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        });
    });
