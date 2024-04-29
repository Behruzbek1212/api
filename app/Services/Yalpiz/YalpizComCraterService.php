<?php

namespace App\Services\MehriGiyoService;

use App\Models\Yalpiz\YalpizComCrater;
use Nette\Utils\Random;

class YalpizComCraterService
{

    public static function getInstance():  YalpizComCraterService
    {
        return new static();
    }

    public function store($request)
    {
        YalpizComCrater::query()->updateOrCreate(
            ['token' => $request->token],
            ['data' => $request->data, 'message_id' => $request->message_id]
        );
    }

    public function storeFile($request)
    {
        $imageName = time() . '-' .  Random::generate(5, 'a-z'). '.' . $request->file('file')->extension();
        $imagefileUrl = 'yalpizFile/' . $imageName;
        $storagePath = public_path($imagefileUrl);
        $request->file('file')->move(public_path('yalpizFile'), $imageName);
        $fileUrl = 'https://static.jobo.uz/' . $imagefileUrl;

        $vacancy = YalpizComCrater::updateOrCreate(
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
        $data = YalpizComCrater::where('token', $token)->first();

        return $data;
    }



}
