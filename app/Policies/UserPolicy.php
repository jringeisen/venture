<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id === $user->parent_id
            || $authUser->id === $user->id
            || $authUser->isAdmin();
    }

    public function update(User $authUser, User $user): bool
    {
        return $authUser->id === $user->parent_id
            || $authUser->id === $user->id
            || $authUser->isAdmin();
    }

    public function delete(User $authUser, User $user): bool
    {
        return $authUser->id === $user->parent_id
            || $authUser->id === $user->id
            || $authUser->isAdmin();
    }
}
