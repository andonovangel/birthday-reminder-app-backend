<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    /**
     * Determine whether the user can perform the given action on the model.
     */
    public function authorize(User $user, Group $group): Response
    {
        return $user->id === $group->user_id
                ? Response::allow() 
                : Response::denyWithStatus(404);
    }
}
