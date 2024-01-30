<?php

namespace App\Http\Controllers\Api;

use App\DTO\GroupDTO;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\{BirthdayListRequest, GroupStoreRequest, GroupUpdateRequest};
use App\Models\Group;
use App\Services\BirthdayService;
use App\Services\GroupService;
use Illuminate\Http\{JsonResponse, Response};

class GroupController extends Controller
{
    public function __construct(private GroupService $groupService, private BirthdayService $birthdayService) {}

    public function index(): JsonResponse 
    {
        $groups = $this->groupService->findAll();
        
        if ($groups->isEmpty()) {
            throw new NotFoundException('No groups found');
        }
        
        return response()->json($groups, Response::HTTP_OK);
    }

    public function show(Group $group): JsonResponse 
    {
        $this->authorize('authorize', $group);
        return response()->json($group, Response::HTTP_OK);
    }

    public function list(Group $group, BirthdayListRequest $request): JsonResponse 
    {
        $birthdays = $this->birthdayService->findAll()
            ->where('group_id', $group->id)
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })->get();

        return response()->json($birthdays, Response::HTTP_OK);
    }

    public function search(string $search): JsonResponse 
    {
        $groups = $this->groupService->search($search);
        
        if ($groups->isEmpty()) {
            throw new NotFoundException('No groups found');
        }
        
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
    
    public function update(GroupUpdateRequest $request, Group $group): JsonResponse
    {
        $this->authorize('authorize', $group);

        return response()->json(
            $this->groupService->updateGroup(
                $group, GroupDTO::fromUpdateRequest($request, auth()->user()->id)
            ), Response::HTTP_OK
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
        
        if (!$group->trashed()) {
            throw new NotFoundException('Group not found');
        }

        $group->restore();

        return response()->json([
            'message' => "Group with id: '$group->id' was successfuly restored"
        ], Response::HTTP_OK);
    }

    public function archived(): JsonResponse
    {
        $groups = $this->groupService->findAllTrashed();

        if ($groups->isEmpty()) {
            throw new NotFoundException('No groups are archived');
        }
        
        return response()->json($groups, Response::HTTP_OK);
    }
}
