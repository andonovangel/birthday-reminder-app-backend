<?php

namespace App\Policies;

use App\Models\Birthday;
use App\Models\User;

class BirthdayPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given post can be updated by the user.
     */
    public function update(User $user, Birthday $birthday): bool
    {
        return $user->id === $birthday->user_id;
    }
}
