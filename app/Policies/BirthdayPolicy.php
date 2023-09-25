<?php

namespace App\Policies;

use App\Models\Birthday;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BirthdayPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Birthday $birthday): Response
    {
        return $user->id === $birthday->user_id
                ? Response::allow()
                : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Birthday $birthday): Response
    {
        return $user->id === $birthday->user_id
                ? Response::allow()
                : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Birthday $birthday): Response
    {
        return $user->id === $birthday->user_id
                ? Response::allow()
                : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Birthday $birthday): Response
    {
        return $user->id === $birthday->user_id
                ? Response::allow()
                : Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Birthday $birthday): Response
    {
        return $user->id === $birthday->user_id
                ? Response::allow()
                : Response::denyWithStatus(404);
    }
}
