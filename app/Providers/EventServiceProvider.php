<?php

namespace App\Providers;

use App\Listeners\StripeEventListener;
use App\Models\Feedback;
use App\Models\NewsletterList;
use App\Models\PromptQuestion;
use App\Models\User;
use App\Observers\FeedbackObserver;
use App\Observers\NewsletterListObserver;
use App\Observers\PromptQuestionObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        WebhookReceived::class => [
            StripeEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        PromptQuestion::observe(PromptQuestionObserver::class);
        NewsletterList::observe(NewsletterListObserver::class);
        User::observe(UserObserver::class);
        Feedback::observe(FeedbackObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
