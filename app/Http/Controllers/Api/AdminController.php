<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{Response, JsonResponse, Request};

class AdminController extends Controller
{

    public function index(): JsonResponse {
        return response()->json(User::get(), Response::HTTP_OK);
    }

    public function update(Request $request, int $userId): JsonResponse {
        $user = User::where('id', $userId)->first();
        $role = $request->role;

        if (in_array($role, config('constants.user-roles'))) {
            $user->update(['role' => $role]);
            return response()->json($user, Response::HTTP_OK);
        }
        else {
            return response()->json('Invalid role was entered', Response::HTTP_BAD_REQUEST);
        }
    }
}
