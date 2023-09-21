<?php

namespace App\Services;
use App\DTO\GroupDTO;
use App\Models\Group;

class GroupService
{
    public function findAll(): mixed {
        return Group::where('user_id', auth()->user()->id)->get();
    }

    public function findGroup(string $id): Group {
        return Group::where('user_id', auth()->user()->id)->findOrFail($id);
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
}