<?php

namespace App\Services;

use App\DTO\GroupDTO;
use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

class GroupService
{
    public function findAll(): mixed {
        return Group::where('user_id', auth()->user()->id)->get();
    }

    public function find(string $id): Group {
        return Group::where('user_id', auth()->user()->id)->findOrFail($id);
    }
    
    public function findAllTrashed(): mixed {
        return Group::where('user_id', auth()->user()->id)->onlyTrashed()->get();
    }

    public function findWithTrashed(string $id): Group {
        return Group::where('user_id', auth()->user()->id)->withTrashed()->findOrFail($id);
    }

    public function search(string $search): Collection {
        return Group::where('user_id', auth()->user()->id)
            ->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('description', 'like', "%$search%");
                    })->get();
    }

    public function createGroup(GroupDTO $groupDTO): Group
    {
        $group = new Group();
        $group->name = $groupDTO->name;
        $group->description = $groupDTO->description;
        $group->user_id = $groupDTO->user_id;

        $group->save();

        return $group;
    }

    public function updateGroup(Group $group, GroupDTO $groupDTO): Group
    {
        return tap($group)->update([
            'name' => $groupDTO->name, 
            'description' => $groupDTO->description,
            'user_id' => $groupDTO->user_id,
        ]);
    }
}