<?php

namespace App\DTO;

class BirthdayDTO
{
    public string $name;
    public string $title;
    public ?string $phone_number;
    public ?string $body;
    public string $birthday_date;
    public string $user_id;
    public ?string $group_id;

    public function __construct(
        string $name,
        string $title,
        ?string $phone_number,
        ?string $body,
        string $birthday_date,
        string $user_id,
        ?string $group_id
    ) {
        $this->name = $name;
        $this->title = $title;
        $this->phone_number = $phone_number;
        $this->body = $body;
        $this->birthday_date = $birthday_date;
        $this->user_id = $user_id;
        $this->group_id = $group_id;
    }
}