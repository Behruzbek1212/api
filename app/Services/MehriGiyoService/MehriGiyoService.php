<?php

namespace App\Services\MehriGiyoService;

use App\Models\MehriGiyo\MehriGiyoHr;

use Nette\Utils\Random;

class MehriGiyoService
{
   
    public static function getInstance(): MehriGiyoService
    {
        return new static();
    }
     
    public function store($request) 
    {
        MehriGiyoHr::query()->updateOrCreate(
            ['token' => $request->token],
            ['data' => $request->data, 'message_id' => $request->message_id]
        );
    }

    public function storeFile($request)
    {
        $imageName = time() . '-' .  Random::generate(5, 'a-z'). '.' . $request->file('file')->extension();
        $imagefileUrl = 'mehriGiyoFile/' . $imageName;
        $storagePath = public_path($imagefileUrl);
        $request->file('file')->move(public_path('mehriGiyoFile'), $imageName);
        $fileUrl = 'https://static.jobo.uz/' . $imagefileUrl;
     
        $vacancy = MehriGiyoHr::updateOrCreate(
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
        $data = MehriGiyoHr::where('token', $token)->first();
        
        return $data;
    }



}