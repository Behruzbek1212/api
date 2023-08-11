<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isCustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // dd($request->user()->customer);
        if (!in_array($request->user()->role, ['admin', 'customer', 'customer_hr'])) {
            return response()->json([
                'status' => false,
                'message' => 'This is not possible for candidates!'
            ]);
        }
        return $next($request);
    }
}
