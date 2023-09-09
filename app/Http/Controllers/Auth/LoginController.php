<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        if (!$loggedIn) return response()->json([
            'status' => false,
            'message' => 'Phone or password is incorrect'
        ]);

        /** @var User $user */
        $user = auth('web')->user();
        $token = $user->createToken(@$user->name ?? 'admin' . '-' . Hash::make($user->id))
            ->plainTextToken;
        $data = User::query()->with('candidate', 'customer')->find($user->id);
        $list = new  UserResource($data);
        return response()->json([
            'status' => true,
            'message' => 'Your account has been successfully authenticated.',
            'user' => $list,
            'token' => $token,
        ]);
    }
}
