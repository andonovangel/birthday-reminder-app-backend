<?php

namespace App\Http\Controllers\Api;

use App\DTO\BirthdayDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\BirthdayStoreRequest;
use App\Models\Birthday;
use App\Services\BirthdayService;
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
            return $this->errorResponse('No birthdays found');
        }
        
        return response()->json($birthdays, Response::HTTP_OK);
    }

    public function show(Birthday $birthday): JsonResponse 
    {
        $this->authorize('authorize', $birthday);
        
        return response()->json($birthday, Response::HTTP_OK);
    }

    public function search(string $search): JsonResponse 
    {
        $birthdays = $this->birthdayService->search($search);
        
        if ($birthdays->isEmpty()) {
            return $this->errorResponse('No birthdays found');
        }
        
        return response()->json($birthdays, Response::HTTP_OK);
    }
    
    public function store(BirthdayStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->birthdayService->createBirthday(
                BirthdayDTO::fromApiRequest($request), 
            ), Response::HTTP_CREATED
        );
    }
    
    public function update(BirthdayStoreRequest $request, Birthday $birthday): JsonResponse
    {
        $this->authorize('authorize', $birthday);
        $birthday = $this->birthdayService->updateBirthday($request, $birthday);

        return response()->json($birthday, Response::HTTP_OK);
    }

    public function destroy(Birthday $birthday): JsonResponse
    {
        $this->authorize('authorize', $birthday);

        if ($birthday->trashed()) {
            $birthday->forceDelete();
        } else {
            $birthday->delete();
        }
        
        return response()->json([
            'message' => "Birthday with id: '$birthday->id' was successfuly deleted"
        ], Response::HTTP_OK);
    }

    public function restore(Birthday $birthday): JsonResponse
    {
        $this->authorize('authorize', $birthday);

        $birthday->restore();

        return response()->json([
            'message' => "Birthday with id: '$birthday->id' was successfuly restored"
        ], Response::HTTP_OK);
    }

    public function archived(): JsonResponse
    {
        $birthdays = $this->birthdayService->findAllTrashed();

        if ($birthdays->isEmpty()) {
            return $this->errorResponse('No birthdays are archived');
        }
        
        return response()->json($birthdays, Response::HTTP_OK);
    }
    
    protected function errorResponse($message, $status = Response::HTTP_NOT_FOUND)
    {
        return response()->json(['message' => $message], $status);
    }
}
