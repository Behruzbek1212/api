<?php

namespace App\Services\Azaly;

use App\Models\Azaly;
use App\Models\Buday\BudayCom;

class AzalyService
{


    public static function getInstance(): AzalyService
    {
        return new static();
    }

    public function store($request)
    {
        Azaly::query()->create([
            'user_id' => $request->user_id,
            'token' => $request->token
        ]);
    }


    public function showData(string $token)
    {
        $data = Azaly::where('token', $token)->first();

        return $data;
    }

}
