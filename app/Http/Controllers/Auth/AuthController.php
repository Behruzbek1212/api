<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }


    public function authenticated(Request $request)
    {

        // dd($request->all());
        $credentials = $request->validate([
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        // dd($credentials);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('payment/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
