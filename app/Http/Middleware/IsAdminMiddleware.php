<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // if ((!_auth()->check()) && (!@$request->user('sanctum')->role == 'admin'))
        if ((_auth()->check()) && (!in_array($request->user('sanctum')->role, ['admin', 'hr', 'recruiter']))) {
            return response()->json([
                'status' => false,
                'message' => 'This is not possible  to enter!'
            ]);
        }
        return $next($request);
    }
}
