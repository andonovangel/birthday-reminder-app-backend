<?php

namespace App\DTO;

use DateTime;

class GroupDTO
{
    public string $name;
    public ?string $description;
    public string $user_id;

    public function __construct(
        string $name, 
        ?string $description,
        string $user_id,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->user_id = $user_id;
    }
}