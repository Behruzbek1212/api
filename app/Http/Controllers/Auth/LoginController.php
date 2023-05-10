<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Login existing user with phone and password.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $loggedIn = auth()->attempt($request->only([
            'phone', 'password'
        ]));

//        dd($loggedIn);

        if (!$loggedIn) return response()->json([
            'status' => false,
            'message' => 'Phone or password is incorrect'
        ]);

        /** @var User $user */
        $user = auth()->user();

        dd($user);
        $token = $user->createToken($user->name . '-' . Hash::make($user->id))
            ->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Your account has been successfully authenticated.',
            'user' => User::query()
                ->with('candidate', 'customer')
                ->find($user->id),
            'token' => $token,
        ]);
    }
}
