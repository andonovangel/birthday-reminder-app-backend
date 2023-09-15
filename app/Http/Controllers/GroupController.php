<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
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

        return view('group/groups', ['birthdays' => $birthdays, 'groups' => $groups]);
    }

    public function createGroup(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);
        $incomingFields['description'] = strip_tags($incomingFields['description']);
        $incomingFields['user_id'] = auth()->id();

        Group::create($incomingFields);
        return redirect('/groups');
    }

    public function showEditGroup(Group $group)
    {
        if (auth()->user()->id !== $group['user_id']) {
            return redirect('/groups');
        }
        return view('group/edit-group', ['group' => $group]);
    }

    public function editGroup(Group $group, Request $request)
    {
        if (auth()->user()->id !== $group['user_id']) {
            return redirect('/groups');
        }

        $incomingFields = $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);
        $incomingFields['description'] = strip_tags($incomingFields['description']);

        $group->update($incomingFields);
        return redirect('/groups');
    }

    public function removeFromGroup(Birthday $birthday) {
        $birthday->group_id = NULL;
        $birthday->update();

        return redirect('/groups');
    }

    public function archivedGroups() {
        $groups = [];
        if (auth()->check()) {
            $groups = auth()->user()->usersGroups()
                    ->latest()->onlyTrashed()->get();
        }

        return view('group/archived-groups', ['groups' => $groups]);
    }

    public function deleteGroup(Group $group)
    {
        if (auth()->user()->id == $group['user_id']) {
            if ($group->trashed()) {
                $group->forceDelete();

                return redirect('/');
            }

            $birthdays = [];
            if (auth()->check()){
                $birthdays = auth()->user()->usersBirthdayReminders()->latest()->get();
            }

            foreach ($birthdays as $birthday) {
                if ($birthday->group_id === $group->id) {
                    $birthday->group_id = NULL;
                    $birthday->update();
                }
            }

            $group->delete();
        }
        return redirect('/groups');
    }

    public function restoreGroup(Group $group, Request $request) {
        $group->restore();

        return redirect('/groups');
    }
}
