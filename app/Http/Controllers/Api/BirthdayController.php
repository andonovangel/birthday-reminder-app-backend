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

    public function index(): JsonResponse 
    {
        $birthdays = $this->birthdayService->findAll();
        
        if ($birthdays->isEmpty()) {
            return response()->json(['message' => 'No birthdays found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json($birthdays);
    }

    public function show(Birthday $birthday): JsonResponse 
    {
        $this->authorize('update', $birthday);
        
        return response()->json($birthday);
    }

    public function search(string $search): JsonResponse 
    {
        $birthdays = $this->birthdayService->search($search);
        
        if ($birthdays->isEmpty()) {
            return response()->json(['message' => 'No birthdays found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json($birthdays);
    }
    
    public function store(BirthdayStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->birthdayService->createBirthday(
                BirthdayDTO::fromApiRequest($request)
            )
        );
    }
    
    public function update(BirthdayStoreRequest $request, Birthday $birthday): JsonResponse
    {
        $this->authorize('update', $birthday);
        $birthday = $this->birthdayService->updateBirthday($request, $birthday);

        return response()->json($birthday);
    }

    public function destroy(Birthday $birthday): JsonResponse
    {
        $this->authorize('delete', $birthday);

        if ($birthday->trashed()) {
            $birthday->forceDelete();
        } else {
            $birthday->delete();
        }
        
        return response()->json([
            'message' => 'Birthday with id: \'' . $birthday->id . '\' was successfuly deleted'
        ]);
    }

    public function restore(Birthday $birthday): JsonResponse
    {
        $this->authorize('delete', $birthday);

        $birthday->restore();

        return response()->json([
            'message' => 'Birthday with id: \'' . $birthday->id . '\' was successfuly restored'
        ]);
    }

    public function archived(): JsonResponse
    {
        $birthdays = $this->birthdayService->findAllTrashed();

        if ($birthdays->isEmpty()) {
            return response()->json(['message' => 'No birthdays are archived'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json($birthdays);
    }
}
