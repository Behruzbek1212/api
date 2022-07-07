<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * 
     * @param  Request  $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        /** @var User $user */
        $user = User::query()->create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
        ]);

        $token = $user->createToken($user->name . '-' . Hash::make($user->id))
            ->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        //
    }
}
