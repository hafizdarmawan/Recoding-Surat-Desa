<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Activity;


class AuthController extends Controller
{
    // use ThrottlesLogins;
    use AuthenticatesUsers;
    
    public function username()
    {
        return 'sin';
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validator($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (Auth::attempt($request->only($this->username(), 'password'), $request->filled('remember'))) {
            Activity::add(['page' => 'Login', 'description' => 'Masuk Ke Website']);
            return redirect()->route('home')->with('status', 'You Are Logged!');
        }

        $this->incrementLoginAttempts($request);
        return $this->loginFailed();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('status', 'You Has Been Looged Out!');
    }

    private function validator(Request $request)
    {
        $rules = [
            $this->username()   => 'required|string|exists:users|min:4|max:191',
            'password'          => 'required|string|min:6|max:255'
        ];

        $message = [
            $this->username() . '.exists'   => 'These Credentials do not match our Records'
        ];
        $request->validate($rules, $message);
    }

    private function loginFailed()
    {
        return redirect()->back()->withInput()->withErrors([
            'password'  => 'Wrong Password'
        ]);
    }
}
