<?php

namespace App\Services\MehriGiyoService;

use App\Models\MehriGiyo\MehriGiyo;


class MehriGiyoUserService
{
    

    public static function getInstance(): MehriGiyoUserService 
    {
        return new static();
    }

    public function store($request) 
    {
        MehriGiyo::query()->create([
            'user_id' => $request->user_id,
            'token' => $request->token
        ]);
    }


    public function showData(string $token)
    {
        $data =   MehriGiyo::where('token', $token)->first();
        
        return $data;
    }

}