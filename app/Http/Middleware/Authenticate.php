<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param Request $request
     * @param  array  $guards
     * @return JsonResponse
     */
    protected function unauthenticated($request, array $guards): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated',
        ]);
    }
}
