<?php

namespace App\Http\Controllers\Api;

use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\{Response, JsonResponse};

class UserController extends Controller
{

    public function __construct() {}

    public function show(): JsonResponse {
        return response()->json(auth()->user(), Response::HTTP_OK);
    }

    public function update(UserUpdateRequest $request): JsonResponse {
        $user = auth()->user();
        $user->update($request->validated());
        event(new UserUpdated($user));

        return response()->json($user, Response::HTTP_OK);
    }
}
