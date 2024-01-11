<?php

namespace App\Http\Controllers\Api;

use App\DTO\BirthdayDTO;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\{BirthdayListRequest, BirthdayStoreRequest, BirthdayUpdateRequest};
use App\Models\Birthday;
use App\Services\BirthdayService;
use Illuminate\Http\{JsonResponse, Response};

class BirthdayController extends Controller
{
    public function __construct(private BirthdayService $birthdayService) {}

    public function index(BirthdayListRequest $request): JsonResponse 
    {
        $birthdays = $this->birthdayService->findAll()
        ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
            $query->orderBy($request->sortBy, $request->sortOrder);
        })->get();
        
        if ($birthdays->isEmpty()) {
            throw new NotFoundException('No birthdays found');
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
            throw new NotFoundException('No birthdays found');
        }
        
        return response()->json($birthdays, Response::HTTP_OK);
    }
    
    public function store(BirthdayStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->birthdayService->createBirthday(
                BirthdayDTO::fromStoreRequest($request, auth()->user()->id), 
            ), Response::HTTP_CREATED
        );
    }
    
    public function update(BirthdayUpdateRequest $request, Birthday $birthday): JsonResponse
    {
        $this->authorize('authorize', $birthday);

        return response()->json(
            $this->birthdayService->updateBirthday(
                $birthday, BirthdayDTO::fromUpdateRequest($request, auth()->user()->id)
            ), Response::HTTP_OK
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
        
        if (!$birthday->trashed()) {
            throw new NotFoundException('Birthday not found');
        }

        $birthday->restore();

        return response()->json([
            'message' => "Birthday with id: '$birthday->id' was successfuly restored"
        ], Response::HTTP_OK);
    }

    public function archived(): JsonResponse
    {
        $birthdays = $this->birthdayService->findAllTrashed();

        if ($birthdays->isEmpty()) {
            throw new NotFoundException('No birthdays are archived');
        }
        
        return response()->json($birthdays, Response::HTTP_OK);
    }
}
