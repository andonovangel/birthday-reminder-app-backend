<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupStoreRequest;
use App\Models\Group;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    public function index(): JsonResponse {
        $groups = Group::all();
        
        return response()->json($groups);
    }

    public function show(string $id): JsonResponse {
        try {
            $group = Group::findOrFail($id);
            return response()->json($group);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function search(string $search): JsonResponse {
        $groups = Group::where(function($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        })->get();
        
        if (!$groups->isEmpty()) {
            return response()->json($groups);
        }
        
        return response()->json([
            'message' => 'No groups found'
        ], Response::HTTP_NOT_FOUND);
    }
    
    public function store(GroupStoreRequest $request): JsonResponse
    {
        $data = $request->validated(); 
        $data['user_id'] = auth()->user()->id;
        $group = Group::create($data);

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
            $group = Group::withTrashed()->findOrFail($id);

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
            $group = Group::onlyTrashed()->findOrFail($id);

            $group->restore();

            return response()->json([
                'message' => 'Group with id: \'' . $id . '\' was successfuly restored'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Group not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function archived() {
        $groups = Group::onlyTrashed()->get();

        if (!$groups->isEmpty()) {
            return response()->json($groups);
        }
        
        return response()->json([
            'message' => 'No groups are archived'
        ], Response::HTTP_NOT_FOUND);
    }
}
