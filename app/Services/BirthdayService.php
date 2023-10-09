<?php

namespace App\Services;

use App\DTO\BirthdayDTO;
use App\Http\Requests\BirthdayUpdateRequest;
use App\Models\Birthday;
use Illuminate\Database\Eloquent\Collection;

class BirthdayService
{
    public function findAll(): mixed {
        return Birthday::where('user_id', auth()->user()->id)->get();
    }

    public function search(string $search): Collection {
        return Birthday::where('user_id', auth()->user()->id)
            ->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('title', 'like', "%$search%") 
                            ->orWhere('phone_number', 'like', "%$search%")
                            ->orWhere('body', 'like', "%$search%");
                    })->get();
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

    public function updateBirthday(Birthday $birthday, BirthdayUpdateRequest $request, $userId): Birthday
    {
        return tap($birthday)->update([
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'phone_number' => $request->input('phone_number'),
            'body' => $request->input('body'),
            'birthday_date' => $request->input('birthday_date'),
            'user_id' => $userId,
            'group_id' => $request->input('group_id')
        ]);
    }
    
    public function findAllTrashed(): mixed {
        return Birthday::where('user_id', auth()->user()->id)->onlyTrashed()->get();
    }
}