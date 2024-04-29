<?php

namespace App\Services\Yalpiz;

use App\Models\Yalpiz\YalpizCom;

class YalpizComService
{


    public static function getInstance(): YalpizComService
    {
        return new static();
    }

    public function store($request)
    {
        YalpizCom::query()->create([
            'user_id' => $request->user_id,
            'token' => $request->token
        ]);
    }


    public function showData(string $token)
    {
        $data =   YalpizCom::where('token', $token)->first();

        return $data;
    }

}
