<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isCustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ( !in_array($request->user()->role, ['admin', 'customer']) ) {
            return response()->json([
                'status' => false,
                'message' => 'This is not possible for customers!'
            ]);
        }
        return $next($request);
    }
}
