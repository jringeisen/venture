<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function view(User $user, Student $student): bool
    {
        return $user->id === $student->user_id;
    }

    public function update(User $user, Student $student): bool
    {
        return $user->id === $student->user_id;
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->id === $student->user_id;
    }
}
