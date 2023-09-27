<?php

namespace App\Policies;

use App\Models\Birthday;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BirthdayPolicy
{

    /**
     * Determine whether the user can perform the given action on the model.
     */
    public function authorize(User $user, Birthday $birthday): Response
    {
        return $user->id === $birthday->user_id
                ? Response::allow() 
                : Response::denyWithStatus(404);
    }
}
