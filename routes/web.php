<?php

use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CourseDayController as AdminCourseDayController;
use App\Http\Controllers\Admin\CoursePromptController as AdminCoursePromptController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Guest\DownloadPlannerController;
use App\Http\Controllers\Guest\NewsletterController;
use App\Http\Controllers\Guest\TermsOfServiceController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\PromptController;
use App\Http\Controllers\Student\Prompts\GetContentController;
use App\Http\Controllers\Student\Prompts\GetQuestionsController;
use App\Http\Controllers\Student\Prompts\GetSubjectController;
use App\Http\Controllers\Student\StudentActivityController;
use App\Http\Controllers\Student\TopicController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Guest Routes...
Route::middleware('guest')->group(static function () {
    Route::get('/', LandingController::class)->name('landing');

    Route::post('/newsletter-lists', [NewsletterController::class, 'subscribe'])->name('newsletter-list.subscribe');
    Route::get('/newsletter-lists/{newsletter_list:email}', [NewsletterController::class, 'unsubscribe'])
        ->name('newsletter-list.unsubscribe')
        ->middleware('signed');

    Route::get('privacy-policy', TermsOfServiceController::class)->name('privacy-policy');
    Route::get('terms-of-service', TermsOfServiceController::class)->name('terms-of-service');

    Route::get('/student-planner/download', DownloadPlannerController::class)->name('student-planner.download');

    Route::get('/blog-posts', [BlogController::class, 'index'])->name('blog-posts.index');
    Route::get('/blog-posts/{blogPost:slug}', [BlogController::class, 'show'])->name('blog-posts.show');
});

// Public Routes (accessible to guests and authenticated users)...
Route::get('/pricing', fn () => redirect('/#pricing'))->name('pricing');

// Authenticated Routes...
Route::middleware('auth')->group(function () {
    // Verified Routes...
    Route::middleware('verified')->group(function () {
        // Admin Routes
        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Courses
            Route::resource('courses', AdminCourseController::class)->except(['show']);
            Route::post('courses/{course}/generate-weeks', [AdminCourseController::class, 'generateWeeks'])->name('courses.generate-weeks');
            Route::post('courses/{course}/generate-all-content', [AdminCourseController::class, 'generateAllContent'])->name('courses.generate-all-content');
            Route::get('courses/{course}/content-progress', [AdminCourseController::class, 'contentGenerationProgress'])->name('courses.content-progress');

            // Course Weeks (nested under courses)
            Route::get('courses/{course}/weeks/create', [AdminCoursePromptController::class, 'create'])->name('courses.weeks.create');
            Route::post('courses/{course}/weeks', [AdminCoursePromptController::class, 'store'])->name('courses.weeks.store');
            Route::get('courses/{course}/weeks/{prompt}/edit', [AdminCoursePromptController::class, 'edit'])->name('courses.weeks.edit');
            Route::put('courses/{course}/weeks/{prompt}', [AdminCoursePromptController::class, 'update'])->name('courses.weeks.update');
            Route::delete('courses/{course}/weeks/{prompt}', [AdminCoursePromptController::class, 'destroy'])->name('courses.weeks.destroy');
            Route::post('courses/{course}/weeks/{prompt}/generate-content', [AdminCoursePromptController::class, 'generateContent'])->name('courses.weeks.generate-content');

            // Course Days (nested under weeks)
            Route::get('courses/{course}/weeks/{prompt}/days/create', [AdminCourseDayController::class, 'create'])->name('courses.weeks.days.create');
            Route::post('courses/{course}/weeks/{prompt}/days', [AdminCourseDayController::class, 'store'])->name('courses.weeks.days.store');
            Route::get('courses/{course}/weeks/{prompt}/days/{day}/edit', [AdminCourseDayController::class, 'edit'])->name('courses.weeks.days.edit');
            Route::put('courses/{course}/weeks/{prompt}/days/{day}', [AdminCourseDayController::class, 'update'])->name('courses.weeks.days.update');
            Route::delete('courses/{course}/weeks/{prompt}/days/{day}', [AdminCourseDayController::class, 'destroy'])->name('courses.weeks.days.destroy');
            Route::post('courses/{course}/weeks/{prompt}/days/{day}/generate-content', [AdminCourseDayController::class, 'generateContent'])->name('courses.weeks.days.generate-content');

            // Blog Posts
            Route::resource('blog-posts', AdminBlogPostController::class)->except(['show']);

            // Blog Categories
            Route::resource('blog-categories', AdminBlogCategoryController::class)->except(['show']);

            // Users
            Route::resource('users', AdminUserController::class)->except(['create', 'store']);

            // Feedback
            Route::get('feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
            Route::get('feedback/{feedback}', [AdminFeedbackController::class, 'show'])->name('feedback.show');
            Route::put('feedback/{feedback}', [AdminFeedbackController::class, 'update'])->name('feedback.update');
            Route::delete('feedback/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');
        });
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
        Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
        Route::patch('/feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
        Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');

        Route::middleware('parent')->prefix('parent')->name('parent.')->group(function () {
            Route::get('/dashboard', DashboardController::class)->name('dashboard');

            Route::prefix('users')->name('users.')->group(static function () {
                Route::get('/', [StudentController::class, 'index'])->name('index');
                Route::get('/create', [StudentController::class, 'create'])->name('create');
                Route::post('/', [StudentController::class, 'store'])->name('store');
                Route::get('/{user}', [StudentController::class, 'show'])->name('show');
                Route::get('/{user}/edit', [StudentController::class, 'edit'])->name('edit');
                Route::patch('/{user}', [StudentController::class, 'update'])->name('update');
                Route::delete('/{user}', [StudentController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('subscription')->name('subscription.')->group(static function () {
                Route::get('/', [SubscriptionController::class, 'index'])->name('index');
                Route::post('/checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
                Route::get('/success', [SubscriptionController::class, 'success'])->name('success');
                Route::get('/billing-portal', [SubscriptionController::class, 'billingPortal'])->name('billing-portal');
                Route::post('/swap', [SubscriptionController::class, 'swap'])->name('swap');
            });

            Route::prefix('attendance')->name('attendance.')->group(static function () {
                Route::get('/', [AttendanceController::class, 'index'])->name('index');
                Route::post('/', [AttendanceController::class, 'store'])->name('store');
                Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('update');
                Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
                Route::get('/daily-summary', [AttendanceController::class, 'dailySummary'])->name('daily-summary');
            });

            Route::prefix('compliance')->name('compliance.')->group(static function () {
                Route::get('/', [ComplianceController::class, 'index'])->name('index');
                Route::get('/reports/create', [ComplianceController::class, 'create'])->name('reports.create');
                Route::post('/reports', [ComplianceController::class, 'store'])->name('reports.store');
                Route::get('/reports/{complianceReport}', [ComplianceController::class, 'show'])->name('reports.show');
                Route::get('/reports/{complianceReport}/pdf', [ComplianceController::class, 'downloadPdf'])->name('reports.pdf');
                Route::delete('/reports/{complianceReport}', [ComplianceController::class, 'destroy'])->name('reports.destroy');
            });
        });

        Route::middleware('student')->prefix('student')->name('student.')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/prompts', [PromptController::class, 'index'])->name('prompts.index');
            Route::post('/prompts', [PromptController::class, 'store'])->middleware('check.question.limit')->name('prompts.store');
            Route::post('/prompts/subject', GetSubjectController::class)->name('prompts.subject');
            Route::get('/prompts/content', GetContentController::class)->name('prompts.content');
            Route::post('/prompts/questions', GetQuestionsController::class)->name('prompts.questions');

            Route::get('/topic/{topic}', [TopicController::class, 'show'])->name('topic.show');

            // Course routes
            Route::prefix('courses')->name('courses.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Student\CourseController::class, 'index'])->name('index');
                Route::get('/enrolled', [\App\Http\Controllers\Student\CourseController::class, 'enrolled'])->name('enrolled');
                Route::get('/search', [\App\Http\Controllers\Student\CourseController::class, 'search'])->name('search');
                Route::get('/subject/{subject}', [\App\Http\Controllers\Student\CourseController::class, 'bySubject'])->name('by-subject');
                Route::get('/{course}', [\App\Http\Controllers\Student\CourseController::class, 'show'])->name('show');
                Route::post('/{course}/enroll', [\App\Http\Controllers\Student\CourseController::class, 'enroll'])->name('enroll');

                // Course learning routes
                Route::get('/{course}/learn/{week?}/{day?}', [\App\Http\Controllers\Student\CourseLearningController::class, 'learn'])->name('learn');
                Route::get('/{course}/week/{week}/content', [\App\Http\Controllers\Student\CourseLearningController::class, 'getContent'])->name('content');
                Route::post('/{course}/progress', [\App\Http\Controllers\Student\CourseLearningController::class, 'updateProgress'])->name('update-progress');

                // Time tracking routes
                Route::post('/{course}/week/{week}/day/{day}/start-session', [\App\Http\Controllers\Student\CourseLearningController::class, 'startSession'])->name('start-session');
                Route::post('/{course}/week/{week}/day/{day}/track-time', [\App\Http\Controllers\Student\CourseLearningController::class, 'trackTime'])->name('track-time');
                Route::post('/{course}/end-session', [\App\Http\Controllers\Student\CourseLearningController::class, 'endSession'])->name('end-session');

                // Course progress routes
                Route::post('/{course}/week/{week}/complete', [\App\Http\Controllers\Student\CourseProgressController::class, 'completeWeek'])->name('complete-week');
                Route::post('/{course}/week/{week}/day/{day}/complete', [\App\Http\Controllers\Student\CourseProgressController::class, 'completeDay'])->name('complete-day');

                // Course trivia routes
                Route::get('/{course}/week/{week}/trivia', [\App\Http\Controllers\Student\CourseProgressController::class, 'getTrivia'])->name('trivia');
                Route::post('/{course}/week/{week}/trivia', [\App\Http\Controllers\Student\CourseProgressController::class, 'submitTrivia'])->name('submit-trivia');

                // Certificate route
                Route::get('/{course}/certificate', [\App\Http\Controllers\Student\CourseCertificateController::class, 'certificate'])->name('certificate');
            });

            Route::patch('/attendance/today', [StudentAttendanceController::class, 'update'])->name('attendance.update');

            Route::patch('/users/{user}', [StudentController::class, 'update'])->name('users.update');

            Route::post('/activity/update', [StudentActivityController::class, 'update'])->name('activity.update');
            Route::post('/activity/persist', [StudentActivityController::class, 'store'])->name('activity.store');
        });

        Route::get('/users/{user}/impersonate-user', [ImpersonationController::class, 'start'])->name('users.start.impersonating');
        Route::get('/users/stop-impersonating', [ImpersonationController::class, 'stop'])->name('users.stop.impersonating');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
