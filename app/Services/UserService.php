<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;

class UserService
{
    public function createUser(UserDTO $userDTO): User
    {
        $user = new User();
        $user->username = $userDTO->username;
        $user->name = $userDTO->name;
        $user->surname = $userDTO->surname;
        $user->email = $userDTO->email;
        $user->password = $userDTO->password;
        $user->last_login = now();
        $user->save();

        return $user;
    }
}