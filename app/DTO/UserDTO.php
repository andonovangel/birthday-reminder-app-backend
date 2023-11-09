<?php

namespace App\DTO;

use Illuminate\Http\Request;

class UserDTO
{
    public string $name;
    public string $email;
    public string $password;

    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromRequest(Request $request): UserDTO {
        return new self(
            $request->input('name'),
            $request->input('email'),
            $request->input('password')
        );
    }
}