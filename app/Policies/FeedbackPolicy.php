<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    public function update(User $user, Feedback $feedback): bool
    {
        return $user->id === $feedback->user_id;
    }

    public function delete(User $user, Feedback $feedback): bool
    {
        return $user->id === $feedback->user_id;
    }
}
