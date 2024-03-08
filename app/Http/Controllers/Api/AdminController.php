<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\{Response, JsonResponse};

class AdminController extends Controller
{

    public function index(): JsonResponse {
        return response()->json(User::get(), Response::HTTP_OK);
    }

    public function update(UserUpdateRequest $request, int $userId): JsonResponse {
        $user = User::where('id', $userId)->first();

        $user->role = $request->input('role');
        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }
}
