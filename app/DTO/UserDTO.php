<?php

namespace App\DTO;

use Illuminate\Http\Request;

class UserDTO
{
    public string $username;
    public string $name;
    public string $surname;
    public string $email;
    public string $password;

    public function __construct(string $username, string $name, string $surname, string $email, string $password)
    {
        $this->username = $username;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromRequest(Request $request): UserDTO {
        return new self(
            $request->input('username'),
            $request->input('name'),
            $request->input('surname'),
            $request->input('email'),
            $request->input('password')
        );
    }
}