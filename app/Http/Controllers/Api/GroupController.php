<?php

namespace App\Http\Controllers\Api;

use App\DTO\GroupDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\{BirthdayListRequest, GroupStoreRequest, GroupUpdateRequest};
use App\Models\Birthday;
use App\Services\BirthdayService;
use App\Services\GroupService;
use Exception;
use Illuminate\Http\{JsonResponse, Response};

class GroupController extends Controller
{
    public function __construct(private GroupService $groupService, private BirthdayService $birthdayService) {}

    public function index(): JsonResponse 
    {
        $groups = $this->groupService->findAll();        
        return response()->json($groups, Response::HTTP_OK);
    }

    public function show(string $id): JsonResponse 
    {
        try {
            $group = $this->groupService->find($id);
            return response()->json($group, Response::HTTP_OK);
        } catch(Exception $e) {
            return  response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function list(string $id, BirthdayListRequest $request): JsonResponse 
    {
        $birthdays = $this->birthdayService->findAll()
            ->where('group_id', $id)
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })->get();

        return response()->json($birthdays, Response::HTTP_OK);
    }

    public function search(string $search): JsonResponse 
    {
        $groups = $this->groupService->search($search);        
        return response()->json($groups, Response::HTTP_OK);
    }
    
    public function store(GroupStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->groupService->createGroup(
                GroupDTO::fromStoreRequest($request, auth()->user()->id), 
            ), Response::HTTP_OK
        );
    }
    
    public function update(GroupUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $group = $this->groupService->find($id);
            return response()->json(
                $this->groupService->updateGroup(
                    $group, GroupDTO::fromUpdateRequest($request, auth()->user()->id)
                ), Response::HTTP_OK
            );
        } catch(Exception $e) {
            return  response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $group = $this->groupService->findWithTrashed($id);

            $birthdays = $this->birthdayService->findAll()->where('group_id', $id)->get();
            Birthday::whereIn('id', $birthdays->pluck('id')->toArray())
                ->update(['group_id' => null]);
                
            $group->trashed() ? $group->forceDelete() : $group->delete();
            
            return response()->json([
                'message' => "Group with id: '$group->id' was successfuly deleted"
            ], Response::HTTP_OK);
        } catch(Exception $e) {
            return  response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $group = $this->groupService->findWithTrashed($id);
            if (!$group->trashed()) {
                return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
            }

            $group->restore();
    
            return response()->json([
                'message' => "Group with id: '$group->id' was successfuly restored"
            ], Response::HTTP_OK);
        } catch(Exception $e) {
            return  response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function archived(): JsonResponse
    {
        $groups = $this->groupService->findAllTrashed();        
        return response()->json($groups, Response::HTTP_OK);
    }
}
