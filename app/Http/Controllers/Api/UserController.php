<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request) {
        $incomiongFields = $request->validate([
            'name' => ['required', 'min:3', 'max:10', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:20']
        ]);

        $incomiongFields['password'] = bcrypt($incomiongFields['password']);
        $user = User::create($incomiongFields);
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()
                ->json(['user' => $user,
                        'token' => $token]);
    }
    
    public function login(Request $request) {
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

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
