<?php

namespace App\Http\Controllers;

use App\DTO\GroupDTO;
use App\Http\Requests\{GroupStoreRequest, GroupUpdateRequest};
use App\Models\{Birthday, Group};
use App\Services\{BirthdayService, GroupService};
use Illuminate\Http\Request;

class GroupController extends Controller
{
    private BirthdayService $birthdayService;
    private GroupService $groupService;

    public function __construct(BirthdayService $birthdayService, GroupService $groupService)
    {
        $this->birthdayService = $birthdayService;
        $this->groupService = $groupService;
    }
    
    public function index()
    {
        $birthdays = [];
        $groups = [];
        if (auth()->check()){
            $birthdays = $this->birthdayService->findAll();
            $groups = $this->groupService->findAll();
        }

        return view('group/groups', ['birthdays' => $birthdays, 'groups' => $groups]);
    }

    public function createGroup(GroupStoreRequest $request)
    {
        $this->groupService->createGroup(
            GroupDTO::fromStoreRequest($request, auth()->user()->id), 
        );

        return redirect('/groups');
    }

    public function showEditGroup(Group $group)
    {
        if (auth()->user()->id !== $group->user_id) {
            return redirect('/groups');
        }
        return view('group/edit-group', ['group' => $group]);
    }

    public function editGroup(Group $group, GroupUpdateRequest $request)
    {
        if (auth()->user()->id !== $group->user_id) {
            return redirect('/groups');
        }

        $this->groupService->updateGroup(
            $group, GroupDTO::fromUpdateRequest($request, $group->user_id)
        );

        return redirect('/groups');
    }

    public function removeFromGroup(Birthday $birthday)
    {
        $birthday->group_id = NULL;
        $birthday->update();

        return redirect('/groups');
    }

    public function archivedGroups()
    {
        $groups = [];
        if (auth()->check()) {
            $groups = $this->groupService->findAllTrashed();
        }

        return view('group/archived-groups', ['groups' => $groups]);
    }

    public function deleteGroup(Group $group)
    {
        if (auth()->user()->id == $group->user_id) {
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

    public function restoreGroup(Group $group)
    {
        $group->restore();

        return redirect('/groups');
    }
}
