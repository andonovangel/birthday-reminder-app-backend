<?php

namespace App\DTO;

use App\Http\Requests\{BirthdayStoreRequest, BirthdayUpdateRequest};
use Illuminate\Http\Request;

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

    public static function fromRequest(Request $request, string $userId): BirthdayDTO {
        return new self(
            $request->input('name'),
            $request->input('title'),
            $request->input('phone_number'),
            $request->input('body'),
            $request->input('birthday_date'),
            $userId,
            $request->input('group_id')
        );
    }

    public static function fromStoreRequest(BirthdayStoreRequest $request, $userId): BirthdayDTO {
        return self::fromRequest($request, $userId);
    }

    public static function fromUpdateRequest(BirthdayUpdateRequest $request, $userId): BirthdayDTO {
        return self::fromRequest($request, $userId);
    }
}