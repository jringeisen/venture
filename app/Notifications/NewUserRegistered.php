<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackMessage;

class NewUserRegistered extends Notification
{
    use Queueable;

    public function __construct(
        public User $user
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        if ($this->user->isParent()) {
            return (new SlackMessage)
                ->text('New Parent Registered: '.$this->user->name.' '.$this->user->email);
        }

        if ($this->user->isStudent()) {
            return (new SlackMessage)
                ->text('New Student Registered: '.$this->user->name.' '.$this->user->email);
        }

        return (new SlackMessage)
            ->text('New User Registered: '.$this->user->name.' '.$this->user->email);
    }
}
