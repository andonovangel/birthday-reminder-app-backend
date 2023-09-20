<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
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
    public function update(User $user, Group $group): bool
    {
        return $user->id === $group->user_id;
    }
}
