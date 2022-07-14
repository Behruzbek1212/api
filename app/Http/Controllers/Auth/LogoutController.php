<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\Contracts\HasAbilities;

class LogoutController extends Controller
{
    /**
     * Log out user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User */
        $user = $request->user();

        /** @var HasAbilities|Builder */
        $token = $user->currentAccessToken();
        $token->delete();

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }
}
