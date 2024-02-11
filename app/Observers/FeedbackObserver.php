<?php

namespace App\Observers;

use App\Models\Feedback;
use App\Notifications\FeedbackCreated;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;

class FeedbackObserver
{
    public function created(Feedback $feedback): void
    {
        if (App::isProduction()) {
            Notification::route('slack', '#user-feedback')
                ->notify(new FeedbackCreated($feedback));
        }
    }
}
