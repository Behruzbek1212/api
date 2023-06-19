<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ((!_auth()->check()) && (!@$request->user('sanctum')->role == 'admin'))
            return throw new NotFoundHttpException('Method not found');

        return $next($request);
    }
}
