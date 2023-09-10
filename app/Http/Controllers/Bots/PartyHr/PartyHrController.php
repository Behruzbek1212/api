<?php

namespace App\Http\Controllers\Bots\PartyHr;

use App\Http\Controllers\Controller;
use App\Models\PartyHr;

use Illuminate\Http\Request;
class PartyHrController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'uuid' => ['uuid', 'required'],
            'identification' => ['string', 'required'],
            'info' => ['array', 'required']
        ]);

        $data = PartyHr::query()->firstOrCreate([
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

        $model = PartyHr::query()->where('uuid', '=', $credentials['identification']);
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

        $data = PartyHr::query()->where('uuid', '=', $credentials['identification'])
            ->first(['info']);

        $crate = PartyHr::query()->where('uuid', '=', $credentials['identification'])
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

        $data = PartyHr::query()->where('telegram_id', '=', $credentials['telegram_id'])
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
