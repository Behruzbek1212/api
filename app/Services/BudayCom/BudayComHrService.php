<?php

namespace App\Services\BudayCom;

use App\Models\Buday\BudayComHr;
use Nette\Utils\Random;

class BudayComHrService
{

    public static function getInstance():  BudayComHrService
    {
        return new static();
    }

    public function store($request)
    {
        BudayComHr::query()->updateOrCreate(
            ['token' => $request->token],
            ['data' => $request->data, 'message_id' => $request->message_id]
        );
    }

    public function storeFile($request)
    {
        $imageName = time() . '-' .  Random::generate(5, 'a-z'). '.' . $request->file('file')->extension();
        $imagefileUrl = 'budayFile/' . $imageName;
        $storagePath = public_path($imagefileUrl);
        $request->file('file')->move(public_path('budayFile'), $imageName);
        $fileUrl = 'https://static.jobo.uz/' . $imagefileUrl;

        $vacancy = BudayComHr::updateOrCreate(
            ['token' => $request->token],
            ['file' => $fileUrl]
        );

        return [
            'message' => 'Successfully',
            'file' => $fileUrl
        ];

    }

    public function showData(string $token)
    {
        $data = BudayComHr::where('token', $token)->first();

        return $data;
    }



}
