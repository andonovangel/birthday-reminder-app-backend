<?php

namespace App\Http\Controllers\Api;

use App\DTO\BirthdayDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\BirthdayStoreRequest;
use App\Http\Resources\Api\{BirthdayCollection, BirthdayResource};
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

    public function index(): BirthdayCollection 
    {
        $birthdays = $this->birthdayService->findAll();
        
        if ($birthdays->isEmpty()) {
            return $this->errorResponse('No birthdays found');
        }
        
        return BirthdayCollection::make(
            $birthdays,
        );
    }

    public function show(Birthday $birthday): BirthdayResource 
    {
        $this->authorize('authorize', $birthday);
        
        return BirthdayResource::make(
            $birthday,
        );
    }

    public function search(string $search): BirthdayCollection 
    {
        $birthdays = $this->birthdayService->search($search);
        
        if ($birthdays->isEmpty()) {
            return $this->errorResponse('No birthdays found');
        }
        
        return BirthdayCollection::make(
            $birthdays,
        );
    }
    
    public function store(BirthdayStoreRequest $request): BirthdayResource
    {
        return BirthdayResource::make(
            $this->birthdayService->createBirthday(
                BirthdayDTO::fromRequest($request), 
            ),
        );
    }
    
    public function update(BirthdayStoreRequest $request, Birthday $birthday): BirthdayResource
    {
        $this->authorize('authorize', $birthday);

        return BirthdayResource::make(
            $this->birthdayService->updateBirthday(
                $birthday, BirthdayDTO::fromRequest($request)
            ),
        );
    }

    public function destroy(Birthday $birthday): JsonResponse
    {
        $this->authorize('authorize', $birthday);

        $birthday->trashed() ? $birthday->forceDelete() : $birthday->delete();
        
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

    public function archived(): BirthdayCollection
    {
        $birthdays = $this->birthdayService->findAllTrashed();

        if ($birthdays->isEmpty()) {
            return $this->errorResponse('No birthdays are archived');
        }
        
        return BirthdayCollection::make(
            $birthdays,
        );
    }
    
    protected function errorResponse($message, $status = Response::HTTP_NOT_FOUND)
    {
        return response()->json(['message' => $message], $status);
    }
}
