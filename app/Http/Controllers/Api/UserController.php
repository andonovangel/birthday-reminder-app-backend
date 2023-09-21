<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\{Request, Response, JsonResponse};
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function register(Request $request): JsonResponse {
        $incomiongFields = $request->validate([
            'name' => ['required', 'min:3', 'max:10', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:20']
        ]);

        $incomiongFields['password'] = bcrypt($incomiongFields['password']);
        
        $userDTO = new UserDTO(
            $request->input('name'),
            $request->input('email'),
            $request->input('password')
        );

        $user = $this->userService->createUser($userDTO);
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()
                ->json(['user' => $user,
                        'token' => $token]);
    }
    
    public function login(Request $request): JsonResponse {
        $incomiongFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $incomiongFields['email'])->first();

        if (!$user || !Hash::check($incomiongFields['password'], $user->password)) {
            return response()->json(['message' => 'Bad credentials'], Response::HTTP_UNAUTHORIZED);
        }
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()
                ->json(['user' => $user,
                        'token' => $token]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
