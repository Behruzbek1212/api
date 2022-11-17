<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Change user old password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function change(Request $request): JsonResponse
    {
        $params = $request->validate([
            'old_password' => ['string', 'regex:/[\w\d\+]/i', 'required'],
            'password' => ['string', 'regex:/[\w\d\+]/i', 'required']
        ]);

        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();

        if ( !Hash::check($params['old_password'], $user->password) ) return response()->json([
            'status' => false,
            'message' => 'User old password is not valid'
        ]);

        $user->update(
            ['password' => Hash::make($params['password'])]
        );

        return response()->json([
            'status' => true,
            'message' => 'User password successfully updated'
        ]);
    }
}
