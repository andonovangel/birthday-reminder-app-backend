<?php

namespace App\Http\Controllers\Api;

use App\DTO\BirthdayDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\{BirthdayListRequest, BirthdayStoreRequest, BirthdayUpdateRequest};
use App\Mail\BirthdayMail;
use App\Services\BirthdayService;
use Exception;
use Illuminate\Http\{JsonResponse, Response};
use Illuminate\Support\Facades\Mail;

class BirthdayController extends Controller
{
    public function __construct(private BirthdayService $birthdayService) {}

    public function index(BirthdayListRequest $request): JsonResponse 
    {
        $birthdays = $this->birthdayService->findAll()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('name', 'like', "%$request->search%")
                            ->orWhere('title', 'like', "%$request->search%");
                });
            })
            ->when($request->sortBy && ($request->sortOrder && $request->sortOrder !== 'none'), function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('birthday_date', $request->date);
            })
            ->get();
        
        return response()->json($birthdays, Response::HTTP_OK);
    }

    public function show(string $id): JsonResponse 
    {
        try {
            $birthday = $this->birthdayService->find($id);
            return response()->json($birthday, Response::HTTP_OK);
        } catch(Exception $e) {
            return  response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }
    
    public function store(BirthdayStoreRequest $request): JsonResponse
    {
        return response()->json(
            $this->birthdayService->createBirthday(
                BirthdayDTO::fromStoreRequest($request, auth()->user()->id), 
            ), Response::HTTP_CREATED
        );
    }
    
    public function update(BirthdayUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $birthday = $this->birthdayService->find($id);
            return response()->json(
                $this->birthdayService->updateBirthday(
                    $birthday, BirthdayDTO::fromUpdateRequest($request, auth()->user()->id)
                ), Response::HTTP_OK
            );
        } catch(Exception $e) {
            return  response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $birthday = $this->birthdayService->findWithTrashed($id);
            $birthday->trashed() ? $birthday->forceDelete() : $birthday->delete();
            
            return response()->json([
                'message' => "Birthday with id: '$birthday->id' was successfuly deleted"
            ], Response::HTTP_OK);
        } catch(Exception $e) {
            return  response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }        
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $birthday = $this->birthdayService->findWithTrashed($id);        
            if (!$birthday->trashed()) {
                return response()->json('Birthday not found', Response::HTTP_NOT_FOUND);
            }
    
            $birthday->restore();
    
            return response()->json([
                'message' => "Birthday with id: '$birthday->id' was successfuly restored"
            ], Response::HTTP_OK);
        } catch(Exception $e) {
            return  response()->json(['message' => 'Birthday not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function archived(): JsonResponse
    {
        $birthdays = $this->birthdayService->findAllTrashed();
        return response()->json($birthdays, Response::HTTP_OK);
    }

    public function send(BirthdayStoreRequest $request): JsonResponse
    {
        $birthdayDate = date('m-d', strtotime($request->birthday_date));
        if ($birthdayDate == date('m-d')) {
            Mail::to(auth()->user()->email)->send(new BirthdayMail($request->name, $request->body, $request->phone_number));
        }
        return response()->json(['message' => 'Email reminder was sent.'], Response::HTTP_OK);
    }
}
