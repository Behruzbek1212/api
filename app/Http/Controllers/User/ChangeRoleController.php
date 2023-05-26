<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChangeRoleController extends Controller
{
    /**
     * Update user role
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();

        $user->changeRole($request->input('role'));

        return response()->json([
            'status' => true,
            'message' => 'User role successfully changed'
        ]);
    }

    /**
     * Update user information data's
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateData(Request $request): JsonResponse
    {
        $request->validate([
            'email'=> ['email', 'unique:users,email']
        ]);
        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();

        return $user->updateData($request);
    }

     /**
     * Update user information data's
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCandidateServicesData(Request $request): JsonResponse
    {
        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();
        
        return $user->updateCandidateServices($request);
    }
}