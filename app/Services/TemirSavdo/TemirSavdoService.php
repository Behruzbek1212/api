<?php

namespace App\Services\TemirSavdo;

use App\Models\TemirSavdo\TemirSavdo;

class TemirSavdoService
{


    public static function getInstance(): TemirSavdoService
    {
        return new static();
    }

    public function store($request)
    {
        TemirSavdo::query()->create([
            'user_id' => $request->user_id,
            'token' => $request->token
        ]);
    }


    public function showData(string $token)
    {
        $data = TemirSavdo::where('token', $token)->first();

        return $data;
    }

}
