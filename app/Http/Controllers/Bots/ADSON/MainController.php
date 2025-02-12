<?php

namespace App\Http\Controllers\Bots\ADSON;

use App\Http\Controllers\Controller;
use App\Models\Bot\Adson;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'uuid' => ['uuid', 'required'],
            'identification' => ['string', 'required'],
            'info' => ['array', 'required']
        ]);

        $data = Adson::query()->firstOrCreate([
            'uuid' => $credentials['uuid']
        ], $credentials)->get(['uuid', 'identification', 'info']);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function check(Request $request)
    {
        $credentials = $request->validate([
            'identification' => ['string', 'required'],
            'telegram_id' => ['numeric', 'required']
        ]);

        $model = Adson::query()->where('uuid', '=', $credentials['identification']);
        $model->update(['telegram_id' => $credentials['telegram_id']]);

        $data = $model->first(['uuid', 'identification', 'info']);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function getUrl(Request $request)
    {
        $credentials = $request->validate([
            'identification' => ['string', 'required']
        ]);

        $data = Adson::query()->where('uuid', '=', $credentials['identification'])
            ->first(['info']);

        $crate = Adson::query()->where('uuid', '=', $credentials['identification'])
            ->first()->link;

        return response()->json([
            'status' => true,
            'data' => $data['info'],
            'url' => $crate['url'],
            'image' => $crate['image']
        ]);
    }

    public function getInfo(Request $request)
    {
        $credentials = $request->validate([
            'telegram_id' => ['numeric', 'required']
        ]);

        $data = Adson::query()->where('telegram_id', '=', $credentials['telegram_id'])
            ->first(['info']);

        if ( $data == null ) {
            return response()->json([
                'status' => true,
                'data' => null
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data['info']
        ]);
    }
}
