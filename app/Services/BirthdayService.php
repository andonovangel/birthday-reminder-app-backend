<?php

namespace App\Services;
use App\DTO\BirthdayDTO;
use App\Http\Requests\BirthdayStoreRequest;
use App\Models\Birthday;
use Illuminate\Database\Eloquent\Collection;

class BirthdayService
{
    public function findAll(): mixed {
        return Birthday::where('user_id', auth()->user()->id)->get();
    }

    public function findBirthday(string $id): Birthday {
        return Birthday::where('user_id', auth()->user()->id)->findOrFail($id);
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

    public function updateBirthday(BirthdayStoreRequest $request, Birthday $birthday): Birthday
    {
        $birthday->fill($request->validated());
        $birthday->save();  

        return $birthday;
    }

    public function deleteBirthday(string $id): void {
        $birthday = $this->findBirthday($id);

        if ($birthday->trashed()) {
            $birthday->forceDelete();
        }
        
        $birthday->delete();
    }
    
    public function findAllTrashed(): mixed {
        return Birthday::where('user_id', auth()->user()->id)->onlyTrashed()->get();
    }

    public function findTrashedBirthday(string $id): Birthday {
        return Birthday::where('user_id', auth()->user()->id)->onlyTrashed()->findOrFail($id);
    }
}