<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CheckEmailController extends Controller
{

    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::query()->where('email', $request->email)->first();
   
        if($user == null){
            return response()->json([
                'status' => true,
                'message' => 'Email is not available'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Email is  available'
        ]);
    }

}
