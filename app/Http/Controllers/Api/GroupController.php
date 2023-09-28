<?php

namespace App\Http\Controllers\Api;

use App\DTO\GroupDTO;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Resources\Api\GroupCollection;
use App\Http\Resources\Api\GroupResource;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\{JsonResponse, Response};

class GroupController extends Controller
{
    private GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index(): GroupCollection 
    {
        $groups = $this->groupService->findAll();
        
        if ($groups->isEmpty()) {
            throw new NotFoundException('No groups found');
        }
        
        return GroupCollection::make(
            $groups,
        );
    }

    public function show(Group $group): GroupResource 
    {
        $this->authorize('authorize', $group);
        
        return GroupResource::make(
            $group,
        );
    }

    public function search(string $search): GroupCollection 
    {
        $groups = $this->groupService->search($search);
        
        if ($groups->isEmpty()) {
            throw new NotFoundException('No groups found');
        }
        
        return GroupCollection::make(
            $groups,
        );
    }
    
    public function store(GroupStoreRequest $request): GroupResource
    {
        return GroupResource::make(
            $this->groupService->createGroup(
                GroupDTO::fromRequest($request), 
            ),
        );
    }
    
    public function update(GroupStoreRequest $request, Group $group): GroupResource
    {
        $this->authorize('authorize', $group);

        return GroupResource::make(
            $this->groupService->updateGroup(
                $group, GroupDTO::fromRequest($request)
            ),
        );
    }

    public function destroy(Group $group): JsonResponse
    {
        $this->authorize('authorize', $group);

        $group->trashed() ? $group->forceDelete() : $group->delete();
        
        return response()->json([
            'message' => "Group with id: '$group->id' was successfuly deleted"
        ], Response::HTTP_OK);
    }

    public function restore(Group $group): JsonResponse
    {
        $this->authorize('authorize', $group);

        $group->restore();

        return response()->json([
            'message' => "Group with id: '$group->id' was successfuly restored"
        ], Response::HTTP_OK);
    }

    public function archived(): GroupCollection
    {
        $groups = $this->groupService->findAllTrashed();

        if ($groups->isEmpty()) {
            throw new NotFoundException('No groups are archived');
        }
        
        return GroupCollection::make(
            $groups,
        );
    }
    
    protected function errorResponse($message, $status = Response::HTTP_NOT_FOUND)
    {
        return response()->json(['message' => $message], $status);
    }
}
