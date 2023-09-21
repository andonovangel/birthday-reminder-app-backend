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
        $groupDTO = new GroupDTO(
            $request->input('name'),
            $request->input('description'),
            auth()->user()->id
        );
        
        $group = $this->groupService->createGroup($groupDTO);

        return response()->json($group);
    }
    
    public function edit(GroupStoreRequest $request, string $id): JsonResponse
    {
        try {
            $group = Group::findOrFail($id);
            $group->fill($request->validated());
            $group->save();

            return response()->json($group);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function delete(string $id)
    {
        try {
            $group = Group::where('user_id', auth()->user()->id)->withTrashed()->findOrFail($id);

            if ($group->trashed()) {
                $group->forceDelete();
            }
            
            $group->delete();
            
            return response()->json([
                'message' => 'Group with id: \'' . $id . '\' was successfuly deleted'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function restore(string $id) {
        try {
            $group = Group::where('user_id', auth()->user()->id)->onlyTrashed()->findOrFail($id);

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
            $groups = Group::where('user_id', auth()->user()->id)->onlyTrashed()->get();
            
            if ($groups->isEmpty()) {
                throw new ModelNotFoundException();
            }

            return response()->json($groups);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No groups found'], Response::HTTP_NOT_FOUND);
        }
    }
}
