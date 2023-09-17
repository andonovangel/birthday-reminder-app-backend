<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function index() {
        $user = auth()->user();
        
        $birthdays = [];
        if (auth()->check()){
            $birthdays = $user->usersBirthdayReminders()->latest()->get();
        }

        $groups = [];
        if (auth()->check()){
            $groups = $user->usersGroups()->latest()->get();
        }

        return view('birthday/home', ['birthdays' => $birthdays, 'groups' => $groups]);
    }

    public function createBirthday(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'body' => 'nullable',
            'phone_number' => 'nullable',
            'birthday_date' => 'required',
            'group_id' => 'nullable',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['phone_number'] = strip_tags($incomingFields['phone_number']);
        $incomingFields['birthday_date'] = strip_tags($incomingFields['birthday_date']);
        
        if (!is_numeric($incomingFields['group_id'])) {
            $incomingFields['group_id'] = NULL;
        }
        else {
            $incomingFields['group_id'] = strip_tags($incomingFields['group_id']);
        }
        
        $incomingFields['user_id'] = auth()->id();

        Birthday::create($incomingFields);
        return redirect('/');
    }

    public function showEditBirthday(Birthday $birthday)
    {
        $user = auth()->user();
        if ($user->id !== $birthday->user_id) {
            return redirect('/');
        }
        
        $groups = [];
        if (auth()->check()){
            $groups = $user->usersGroups()->latest()->get();
        }
        
        return view('birthday/edit-birthday', ['birthday' => $birthday, 'groups' => $groups]);
    }

    public function editBirthday(Birthday $birthday, Request $request)
    {
        if (auth()->user()->id !== $birthday->user_id) {
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'body' => 'nullable',
            'phone_number' => 'nullable',
            'birthday_date' => 'required',
            'group_id' => 'nullable',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['phone_number'] = strip_tags($incomingFields['phone_number']);
        $incomingFields['birthday_date'] = strip_tags($incomingFields['birthday_date']);
        
        if (!is_numeric($incomingFields['group_id'])) {
            $incomingFields['group_id'] = NULL;
        }
        else {
            $incomingFields['group_id'] = strip_tags($incomingFields['group_id']);
        }

        $birthday->update($incomingFields);
        return redirect('/');
    }

    public function archivedBirthdays() {
        $user = auth()->user();
        $birthdays = [];
        if (auth()->check()){
            $birthdays = $user->usersBirthdayReminders()
                        ->latest()->onlyTrashed()->get();
        }
        
        $groups = [];
        if (auth()->check()){
            $groups = $user->usersGroups()->latest()->get();
        }

        return view('birthday/archived-birthdays', ['birthdays' => $birthdays, 'groups' => $groups]);
        
    }

    public function deleteBirthday(Birthday $birthday)
    {
        if (auth()->user()->id == $birthday['user_id']) {
            if ($birthday->trashed()) {
                $birthday->forceDelete();

                return redirect('/');
            }
     
            $birthday->delete();
        }
        return redirect('/');
    }

    public function restoreBirthday(Birthday $birthday) {
        $birthday->restore();

        return redirect('/');
    }

    public function listBirthdays(Request $request) {
        $user = auth()->user();

        if ($user) {
            $sort = $request->input('sort', '');
            $search = $request->input('search', '');

            if ($request->input('order') === 'desc') {
                $order = 'desc';
            } else {
                $order = 'asc';
            }

            $birthdays = Birthday::where('user_id', $user->id);

            if ($search !== '') {
                $birthdays->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('title', 'like', "%$search%") 
                            ->orWhere('phone_number', 'like', "%$search%")
                            ->orWhere('body', 'like', "%$search%");
                    });
            }

            if ($sort === 'date') {
                $birthdays->orderBy('birthday_date', $order);
            } else if ($sort === 'alphabetical') {
                $birthdays->orderBy('name', $order);
            }
        }
        
        dump($search);
        dump($sort);
        dump($order);
        $birthdays = $birthdays->get();

        return view('birthday/list-birthdays', compact('birthdays', 'user', 'search', 'sort', 'order'));
    }
}
