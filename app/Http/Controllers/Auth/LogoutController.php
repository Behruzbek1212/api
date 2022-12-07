<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
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
        /** @var Authenticatable|User $request */
        $user = _auth()->user();

        /** @var HasAbilities|Builder $token */
        $token = $user->currentAccessToken();
        $token->delete();

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }
}
