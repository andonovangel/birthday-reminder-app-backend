<?php

namespace App\Services;
use App\DTO\BirthdayDTO;
use App\Models\Birthday;

class BirthdayService
{
    public function findAll(): mixed {
        return Birthday::where('user_id', auth()->user()->id)->get();
    }

    public function findBirthday(string $id): Birthday {
        return Birthday::where('user_id', auth()->user()->id)->findOrFail($id);
    }
    
    public function createBirthday(BirthdayDTO $birthdayDTO): Birthday
    {
        $birthday = new Birthday();
        $birthday->name = $birthdayDTO->name;
        $birthday->title = $birthdayDTO->title;
        $birthday->phone_number = $birthdayDTO->phone_number;
        $birthday->body = $birthdayDTO->body;
        $birthday->birthday_date = $birthdayDTO->birthday_date;
        $birthday->user_id = $birthdayDTO->user_id;
        $birthday->group_id = $birthdayDTO->group_id;

        $birthday->save();

        return $birthday;
    }
}