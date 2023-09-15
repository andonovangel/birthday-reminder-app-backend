<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $incomiongFields = $request->validate([
            'name' => ['required', 'min:3', 'max:10', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:20']
        ]);

        $incomiongFields['password'] = bcrypt($incomiongFields['password']);
        $user = User::create($incomiongFields);
        auth()->login($user);

        return redirect('/');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }

    public function login(Request $request)
    {
        $incomiongFields = $request->validate([
            'loginName' => 'required',
            'loginPassword' => 'required'
        ]);

        if (auth()->attempt(['name' => $incomiongFields['loginName'], 'password' => $incomiongFields['loginPassword']])) {
            $request->session()->regenerate();
        }

        return redirect('/');
    }
}
