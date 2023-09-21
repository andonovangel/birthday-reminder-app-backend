<?php

namespace App\Services;
use App\DTO\GroupDTO;
use App\Models\Group;

class GroupService
{
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