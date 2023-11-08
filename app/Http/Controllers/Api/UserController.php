<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
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
            'name' => ['required', 'max:20', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'confirmationEmail' => ['required', 'email', 'same:email'],
            'password' => ['required', 'min:8', 'max:20'],
            'confirmationPassword' => ['required', 'min:8', 'max:20', 'same:password']
        ], [
            'confirmationEmail.same' => 'Emails do not match.',
            'confirmationPassword.same' => 'Passwords do not match.',
        ]);

        $incomiongFields['password'] = bcrypt($incomiongFields['password']);

        $user = $this->userService->createUser(UserDTO::fromRequest($request));
        
        $token = $user->createToken(time())->plainTextToken;

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
        
        $token = $user->createToken(time())->plainTextToken;

        return response()
                ->json(['user' => $user,
                        'token' => $token]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        $user = auth()->user();
        $user->update($request->validated());
        event(new UserUpdated($user));

        return response()->json($user, Response::HTTP_ACCEPTED);
    }
}
