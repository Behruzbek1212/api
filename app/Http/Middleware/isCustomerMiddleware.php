<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;

class isCustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // dd($request->user()->customer()->active);
        // $request->header('customer_id');
        // if (in_array(_user()->role, ['admin'])) {
        //     return $next($request);
        // }
        // $roles_count = Customer::query()->where('id', $request->header('customer_id'))
        //     ->where('active', true)->count();

        // if ($roles_count > 0) {
        //     if (!in_array(_user()->role, ['admin', 'customer'])) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'This is not possible for candidates!'
        //         ]);
        //     }
        //     return $next($request);
        // }
        // return response()->json([
        //     'status' => false,
        //     'message' => 'This is not possible for candidates! or not active customer'
        // ]);
         if (!in_array($request->user()->role, ['admin', 'customer'])) {
            return response()->json([
                'status' => false,
                'message' => 'This is not possible for candidates!'
            ]);
        }
         return $next($request);
}
}
