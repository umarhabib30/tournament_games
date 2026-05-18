<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function signup()
    {
        return view('user.auth.signup');
    }

    public function login()
    {
        return view('user.auth.login');
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first())->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'role' => '1',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        if ($user) {
            return redirect()->route('tournament')->with('success', 'You Registered Successfully');
        }
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(
            ['username' => $request->username, 'password' => $request->password],
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();
            return redirect()->intended('/tournaments');
        }

        return redirect()->back()->with('error', 'Credentials do not match our records.')->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login')->with('success', 'Logged out successfully');
    }
}
