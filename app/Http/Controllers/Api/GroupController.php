<?php

namespace App\Http\Controllers\Api;

use App\DTO\GroupDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupStoreRequest;
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

    public function index(): JsonResponse 
    {
        $groups = $this->groupService->findAll();
        
        if ($groups->isEmpty()) {
            return $this->errorResponse('No groups found');
        }
        
        return response()->json($groups, Response::HTTP_OK);
    }

    public function show(Group $group): JsonResponse 
    {
        $this->authorize('authorize', $group);
        
        return response()->json($group, Response::HTTP_OK);
    }

    public function search(string $search): JsonResponse 
    {
        $groups = $this->groupService->search($search);
        
        if ($groups->isEmpty()) {
            return $this->errorResponse('No groups found');
        }
        
        return response()->json($groups, Response::HTTP_OK);
    }
    
    public function store(GroupStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->groupService->createGroup(
                GroupDTO::fromApiRequest($request), 
            ), Response::HTTP_CREATED
        );
    }
    
    public function update(GroupStoreRequest $request, Group $group): JsonResponse
    {
        $this->authorize('authorize', $group);
        $group = $this->groupService->updateGroup($request, $group);

        return response()->json($group, Response::HTTP_OK);
    }

    public function destroy(Group $group): JsonResponse
    {
        $this->authorize('authorize', $group);

        if ($group->trashed()) {
            $group->forceDelete();
        } else {
            $group->delete();
        }
        
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

    public function archived(): JsonResponse
    {
        $groups = $this->groupService->findAllTrashed();

        if ($groups->isEmpty()) {
            return $this->errorResponse('No groups are archived');
        }
        
        return response()->json($groups, Response::HTTP_OK);
    }
    
    protected function errorResponse($message, $status = Response::HTTP_NOT_FOUND)
    {
        return response()->json(['message' => $message], $status);
    }
}
