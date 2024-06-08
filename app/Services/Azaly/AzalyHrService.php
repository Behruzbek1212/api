<?php

namespace App\Services\Azaly;

use App\Models\AzalyHr;
use App\Models\Buday\BudayComHr;
use Nette\Utils\Random;

class AzalyHrService
{

    public static function getInstance():  AzalyHrService
    {
        return new static();
    }

    public function store($request)
    {
        AzalyHr::query()->updateOrCreate(
            ['token' => $request->token],
            ['data' => $request->data, 'message_id' => $request->message_id]
        );
    }

    public function storeFile($request)
    {
        $imageName = time() . '-' .  Random::generate(5, 'a-z'). '.' . $request->file('file')->extension();
        $imagefileUrl = 'azalyFile/' . $imageName;
        $storagePath = public_path($imagefileUrl);
        $request->file('file')->move(public_path('azalyFile'), $imageName);
        $fileUrl = 'https://static.jobo.uz/' . $imagefileUrl;

        $vacancy = AzalyHr::updateOrCreate(
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
        $data = AzalyHr::where('token', $token)->first();

        return $data;
    }



}
