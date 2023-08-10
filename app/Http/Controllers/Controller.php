<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function user(Request $request)
    {
        $user = $request->user('sanctum');
        $data = User::query()->with('candidate', 'customer')->find($user->id);
        $list = new  UserResource($data);
        return response()->json([
            'status' => true,
            'user' => $list
        ]);
    }
}
