<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChangeRoleController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        /** @var Authenticatable|User $user */
        $user = _auth()->user();

        $user->changeRole($request->input('role'));

        return response()->json([
            'status' => true,
            'message' => 'User role successfully changed'
        ]);
    }

//    public function updateData(Request $request): JsonResponse
//    {
//        /** @var Authenticatable|User $user */
//        $user = _auth()->user();
//
//        return response()->json([
//            'status' => true,
//            'message' => 'User role successfully updated'
//        ]);
//    }
}
