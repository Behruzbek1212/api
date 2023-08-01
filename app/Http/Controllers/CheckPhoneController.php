<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckPhoneController extends Controller
{
    public function check(Request $request):JsonResponse
    {
        $request->validate([
            'phone' => 'required|numeric'
        ]);

         $phone = User::where('phone', $request->phone)->first();
        
         if($phone == null) {
            return response()->json([
                 'status' => true,
                  'message' => 'Phone is not available'
            ]);
         }

         return response()->json([
              'status' => false,
              'message' => 'Phone is available'
         ]);
    }
}
