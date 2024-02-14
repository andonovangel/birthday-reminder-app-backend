<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\{Request, Response, JsonResponse};
use Illuminate\Support\{Carbon, Str};
use Illuminate\Support\Facades\{Cookie, DB, Hash, Mail};
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct(private UserService $userService) {}
    
    public function register(Request $request): JsonResponse {
        $incomiongFields = $request->validate([
            'username' => ['required', 'max:20', Rule::unique('users', 'username')],
            'name' => ['string'],
            'surname' => ['string'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:20'],
            'confirmationPassword' => ['required', 'min:8', 'max:20', 'same:password']
        ], [
            'confirmationPassword.same' => 'Passwords do not match.',
        ]);

        $incomiongFields['password'] = bcrypt($incomiongFields['password']);

        $user = $this->userService->createUser(UserDTO::fromRequest($request));
        
        $token = $user->createToken(time(), ['*'], now()->addWeek())->plainTextToken;

        return response()
                ->json(['user' => $user], Response::HTTP_OK)
                ->withCookie('token', $token, 24*60*7);
    }
    
    public function login(Request $request): JsonResponse {
        $incomiongFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $incomiongFields['email'])->first();

        if (!$user || !Hash::check($incomiongFields['password'], $user->password)) {
            return response()->json(['message' => 'Incorrect email or password'], Response::HTTP_UNAUTHORIZED);
        }
        
        $request->remember ? 
            $token = $user->createToken(time(), ['*'], now()->addMonth())->plainTextToken : 
            $token = $user->createToken(time(), ['*'], now()->addWeek())->plainTextToken;
        $request->remember ? 
            $expiration = 24*60*30 : 
            $expiration = 24*60*7;

        $user->update(['last_login' => now()]);

        return response()
                ->json(['user' => $user], Response::HTTP_OK)
                ->withCookie('token', $token, $expiration);
    }

    public function logout(): JsonResponse {
        auth()->user()->tokens()->delete();

        return response()
                ->json(['message' => 'Logged out'])
                ->withCookie(Cookie::forget('token'));
    }

    public function sendResetPasswordEmail(Request $request): JsonResponse {
        if (DB::table('password_reset_tokens')->where('email', $request->email)->first()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        }

        $request->validate(['email' => 'required|email']);
        $user = DB::table('users')->where('email', $request->email)->first();

        if ($user === null) {
            return response()->json(['There is no user associated with this email'], Response::HTTP_NOT_FOUND);
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($user->email)->send(new ResetPasswordMail($token));

        return response()->json(['A reset link has been sent to your email address.'], Response::HTTP_OK);
    }

    public function resetPassword(Request $request, string $token): JsonResponse {
        $request->validate([
            'password' => ['required', 'min:8', 'max:20'],
            'confirmationPassword' => ['required', 'min:8', 'max:20', 'same:password']
        ]);

        $password_reset_token = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($password_reset_token === null) {
            return response()->json(['This token is not valid or has expired'], Response::HTTP_FORBIDDEN);
        }

        $email = $password_reset_token->email;

        User::where('email', $email)
            ->update(['password' =>  bcrypt($request->password)]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json(['Password reset successfuly'], Response::HTTP_CREATED);
    }

    public function changePassword(Request $request): JsonResponse {
        $user = auth()->user();
        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json(['currentPassword' => 'You entered the wrong current password'], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'currentPassword' => ['required', 'min:8', 'max:20'],
            'newPassword' => ['required', 'min:8', 'max:20', 'different:currentPassword'],
            'confirmationPassword' => ['required', 'min:8', 'max:20', 'same:newPassword']
        ]);

        $user->update(['password' => bcrypt($request->newPassword)]);

        return response()->json(['Password reset successfuly'], Response::HTTP_CREATED);
    }
}
