<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\NewUserRegistered;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;

class UserObserver
{
    public function created(User $user): void
    {
        if (App::isProduction()) {
            Notification::route('slack', '#signups-cancellations-retention')
                ->notify(new NewUserRegistered($user));
        }
    }
}
