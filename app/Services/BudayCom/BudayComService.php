<?php

namespace App\Services\BudayCom;

use App\Models\Buday\BudayCom;

class BudayComService
{


    public static function getInstance():  BudayComService
    {
        return new static();
    }

    public function store($request)
    {
        BudayCom::query()->create([
            'user_id' => $request->user_id,
            'token' => $request->token
        ]);
    }


    public function showData(string $token)
    {
        $data = BudayCom::where('token', $token)->first();

        return $data;
    }

}
