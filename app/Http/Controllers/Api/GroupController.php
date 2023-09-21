<?php

namespace App\Http\Controllers\Api;

use App\DTO\GroupDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupStoreRequest;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\{JsonResponse, Response};

class GroupController extends Controller
{
    private GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index(): JsonResponse {
        try {
            $groups = $this->groupService->findAll();
            
            if ($groups->isEmpty()) {
                throw new ModelNotFoundException();
            }

            return response()->json($groups);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No groups found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function show(string $id): JsonResponse {
        try {
            $group = $this->groupService->findGroup($id);
            return response()->json($group);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function search(string $search): JsonResponse {
        try {
            $groups = $this->groupService->search($search);
            
            if ($groups->isEmpty()) {
                throw new ModelNotFoundException();
            }
            
            return response()->json($groups);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No groups found'], Response::HTTP_NOT_FOUND);
        }
    }
    
    public function store(GroupStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->groupService->createGroup(
                GroupDTO::fromApiRequest($request)
            )
        );
    }
    
    public function edit(GroupStoreRequest $request, string $id): JsonResponse
    {
        try {
            $group = Group::findOrFail($id);
            $group = $this->groupService->updateGroup($request, $group);

            return response()->json($group);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function delete(string $id)
    {
        try {
            $this->groupService->deleteGroup($id);
            
            return response()->json([
                'message' => 'Group with id: \'' . $id . '\' was successfuly deleted'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function restore(string $id) {
        try {
            $group = $this->groupService->findTrashedGroup($id);

            $group->restore();

            return response()->json([
                'message' => 'Group with id: \'' . $id . '\' was successfuly restored'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function archived() {
        try {
            $groups = $this->groupService->findAllTrashed();
            
            if ($groups->isEmpty()) {
                throw new ModelNotFoundException();
            }

            return response()->json($groups);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No groups found'], Response::HTTP_NOT_FOUND);
        }
    }
}
