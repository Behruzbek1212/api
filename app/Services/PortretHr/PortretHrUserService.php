<?php

namespace App\Services\PortretHr;

use App\Models\PortretHr\PortretUser;
use App\Traits\HasScopes;

use Illuminate\Database\Eloquent\Builder;

class PortretHrUserService
{
    

    public static function getInstance(): PortretHrUserService 
    {
        return new static();
    }

    public function store($request) 
    {
        PortretUser::query()->create([
            'user_id' => $request->user_id,
            'token' => $request->token
        ]);
    }


    public function showData(string $token)
    {
        $data =   PortretUser::where('token', $token)->first();
        
        return $data;
    }

}