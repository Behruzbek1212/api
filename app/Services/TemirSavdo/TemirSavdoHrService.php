<?php

namespace App\Services\TemirSavdo;


use App\Models\TemirSavdo\TemirSavdoHr;
use Nette\Utils\Random;

class TemirSavdoHrService
{

    public static function getInstance():  TemirSavdoHrService
    {
        return new static();
    }

    public function store($request)
    {
        TemirSavdoHr::query()->updateOrCreate(
            ['token' => $request->token],
            ['data' => $request->data, 'message_id' => $request->message_id]
        );
    }

    public function storeFile($request)
    {
        $imageName = time() . '-' .  Random::generate(5, 'a-z'). '.' . $request->file('file')->extension();
        $imagefileUrl = 'temiryoliFile/' . $imageName;
        $storagePath = public_path($imagefileUrl);
        $request->file('file')->move(public_path('temirwayFile'), $imageName);
        $fileUrl = 'https://static.jobo.uz/' . $imagefileUrl;

        $vacancy = TemirSavdoHr::updateOrCreate(
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
        $data = TemirSavdoHr::where('token', $token)->first();
        return $data;
    }



}
