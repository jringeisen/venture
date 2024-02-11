<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    public function view(User $user, Feedback $feedback): bool
    {
        return $user->id === $feedback->user_id
            || in_array($user->email, config('app.nova_admin_emails'));
    }

    public function update(User $user, Feedback $feedback): bool
    {
        return $user->id === $feedback->user_id
            || in_array($user->email, config('app.nova_admin_emails'));
    }

    public function delete(User $user, Feedback $feedback): bool
    {
        return $user->id === $feedback->user_id
            || in_array($user->email, config('app.nova_admin_emails'));
    }
}
