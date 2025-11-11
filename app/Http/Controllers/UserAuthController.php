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
            'username' => 'unique:users,username',
            'email' => 'unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first())->withInput();
        }

        $user = User::updateOrCreate(
            ['email' => $request->email], [
                'username' => $request->username,
                'phone' => $request->phone,
                'role' => '1',
                'password' => Hash::make($request->password),
            ]
        );
        Auth::login($user);
        if ($user) {
            return redirect()->route('tournament')->with('success', 'You Registered Successfully');
        }
    }

    public function authenticate(Request $request)
    {
        // dd($request->all());

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            Auth::login(Auth::user(), true);
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
