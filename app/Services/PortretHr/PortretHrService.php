<?php

namespace App\Services\PortretHr;

use App\Models\PortretHr\PortretHr;

use Illuminate\Database\Eloquent\Builder;
use Nette\Utils\Random;

class PortretHrService
{
   
   

    public static function getInstance():  PortretHrService
    {
        return new static();
    }
     
    public function store($request) 
    {
        PortretHr::query()->updateOrCreate(
            ['token' => $request->token],
            ['data' => $request->data, 'message_id' => $request->message_id]
        );
    }

    public function storeFile($request)
    {
        $imageName = time() . '-' .  Random::generate(5, 'a-z'). '-' . $request->file('file')->extension();
        $imagefileUrl = 'portretHrFile/' . $imageName;
        $storagePath = public_path($imagefileUrl);
        $request->file('file')->move(public_path('portretHrFile'), $imageName);
        $fileUrl = 'https://static.jobo.uz/' . $imagefileUrl;
     
        $vacancy =  PortretHr::updateOrCreate(
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
        $data = PortretHr::where('token', $token)->firstOrFail();
        
        return $data;
    }



}