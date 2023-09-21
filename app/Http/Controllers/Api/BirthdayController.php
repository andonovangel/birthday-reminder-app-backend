<?php

namespace App\Http\Controllers\Api;

use App\DTO\BirthdayDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\BirthdayStoreRequest;
use App\Models\Birthday;
use App\Services\BirthdayService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\{JsonResponse, Response};

class BirthdayController extends Controller
{
    private BirthdayService $birthdayService;

    public function __construct(BirthdayService $birthdayService)
    {
        $this->birthdayService = $birthdayService;
    }

    public function index(): JsonResponse {
        try {
            $birthdays = $this->birthdayService->findAll();
            
            if ($birthdays->isEmpty()) {
                throw new ModelNotFoundException();
            }
            
            return response()->json($birthdays);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No birthdays found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function show(string $id): JsonResponse {
        try {
            $birthday = $this->birthdayService->findBirthday($id);
            return response()->json($birthday);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function search(string $search): JsonResponse {
        try {
            $birthdays = $this->birthdayService->search($search);
            
            if ($birthdays->isEmpty()) {
                throw new ModelNotFoundException();
            }
            
            return response()->json($birthdays);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No birthdays found'], Response::HTTP_NOT_FOUND);
        }
    }
    
    public function store(BirthdayStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->birthdayService->createBirthday(
                BirthdayDTO::fromApiRequest($request)
            )
        );
    }
    
    public function edit(BirthdayStoreRequest $request, string $id): JsonResponse
    {
        try {
            $birthday = Birthday::findOrFail($id);
            $birthday->fill($request->validated());
            $birthday->save();

            return response()->json($birthday);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function delete(string $id)
    {
        try {
            $birthday = Birthday::where('user_id', auth()->user()->id)->withTrashed()->findOrFail($id);

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

    public function restore(string $id) {
        try {
            $birthday = Birthday::where('user_id', auth()->user()->id)->onlyTrashed()->findOrFail($id);

            $birthday->restore();

            return response()->json([
                'message' => 'Birthday with id: \'' . $id . '\' was successfuly restored'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function archived() {
        $birthdays = Birthday::where('user_id', auth()->user()->id)->onlyTrashed()->get();

        if (!$birthdays->isEmpty()) {
            return response()->json($birthdays);
        }
        
        return response()->json([
            'message' => 'No birthdays are archived'
        ], Response::HTTP_NOT_FOUND);
    }
}
