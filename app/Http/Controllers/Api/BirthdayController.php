<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidBirthdayIdException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BirthdayIdRequest;
use App\Http\Requests\BirthdayStoreRequest;
use App\Models\Birthday;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BirthdayController extends Controller
{
    public function index(): JsonResponse {
        $birthdays = Birthday::all();
        
        return response()->json($birthdays);
    }

    public function show(string $id): JsonResponse {
        try {
            $birthday = Birthday::findOrFail($id);
            return response()->json($birthday);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function search(string $search): JsonResponse {
        $birthdays = Birthday::where(function($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('title', 'like', "%$search%") 
                ->orWhere('phone_number', 'like', "%$search%")
                ->orWhere('body', 'like', "%$search%");
        })->get();
        
        if ($birthdays) {
            return response()->json($birthdays);
        }
        
        return response()->json([
            'message' => 'No birthdays found'
        ], Response::HTTP_NOT_FOUND);
    }
    
    public function store(BirthdayStoreRequest $request): JsonResponse
    {
        $brithday = Birthday::create($request->validated());

        return response()->json($brithday);
    }
    
    public function edit(BirthdayStoreRequest $request, string $id): JsonResponse
    {
        try {
            $birthday = Birthday::findOrFail($id);
            $birthday->fill($request->validated());
            $birthday->
            $birthday->save();

            return response()->json($birthday);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function delete(string $id)
    {
        try {
            $birthday = Birthday::withTrashed()->findOrFail($id);

            if ($birthday->trashed()) {
                $birthday->forceDelete();
            }
            
            $birthday->delete();
            
            return response()->json([
                'message' => 'Birthday with id: \'' . $id . '\' was successfuly deleted'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function restore(int $id) {
        try {
            $birthday = Birthday::onlyTrashed()->findOrFail($id);

            $birthday->restore();

            return response()->json([
                'message' => 'Birthday with id: \'' . $id . '\' was successfuly restored'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function archived() {
        $birthdays = Birthday::onlyTrashed()->get();

        return response()->json($birthdays);
    }
}
