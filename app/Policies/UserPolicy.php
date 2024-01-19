<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id === $user->parent_id;
    }

    public function update(User $authUser, User $user): bool
    {
        return $authUser->id === $user->parent_id;
    }

    public function delete(User $authUser, User $user): bool
    {
        return $authUser->id === $user->parent_id;
    }
}
