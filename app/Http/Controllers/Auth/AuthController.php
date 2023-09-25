<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }


    // public function authenticated(Request $request)
    // {

    //     // dd($request->all());
    //     $credentials = $request->validate([
    //         'phone' => ['required', 'numeric'],
    //         'password' => ['required', 'string', 'min:8'],
    //     ]);
    //     // dd($credentials);

    //     if (Auth::attempt($credentials)) {
    //       $das =  $request->session()->regenerate();
    //       dd($das);
    //         return redirect()->intended('payment/projects');
    //     }

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ])->onlyInput('email');
    // }

    // public function authenticated(Request $request)
    // {
    //     $request->validate([
    //         'phone' => ['required', 'numeric'],
    //         'password' => ['required', 'string', 'min:8'],
    //     ]);

    //     $loggedIn = auth()->attempt($request->only([
    //         'phone', 'password'
    //     ]));

    //     if (!$loggedIn) return response()->json([
    //         'status' => false,
    //         'message' => 'Phone or password is incorrect'
    //     ]);
    //     // dd($request->all());

    //     /** @var User $user */
    //     $user = auth('web')->user();
    //     $token = $user->createToken(@$user->name ?? 'admin' . '-' . Hash::make($user->id))
    //         ->plainTextToken;
    //     //         $request->session()->regenerate();
    //     return redirect()->intended('payment/projects');
    //     // $data = User::query()->with('candidate', 'customer')->find($user->id);
    //     // $list = new  UserResource($data);
    //     // return response()->json([
    //     //     'status' => true,
    //     //     'message' => 'Your account has been successfully authenticated.',
    //     //     'user' => $list,
    //     //     'token' => $token,
    //     // ]);
    // }
}
