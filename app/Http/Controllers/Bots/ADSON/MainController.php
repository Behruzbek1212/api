<?php

namespace App\Http\Controllers\Bots\ADSON;

use App\Http\Controllers\Controller;
use App\Models\Bot\Adson;
use App\Models\Bot\AdsonCrater;
use Illuminate\Http\Request;

class AdsonController extends Controller
{
    public function addLinks(Request $request)
    {
        $credentials = $request->validate([
            'identification' => ['string', 'required'],
            'url' => ['string', 'required'],
            'image' => ['string', 'required']
        ]);

        AdsonCrater::query()->updateOrCreate([
            'identification' => $credentials['identification']
        ], $credentials);

        return response()->json([
            'status' => true,
        ]);
    }

    public function getUsers()
    {
        $list = Adson::query()->distinct('telegram_id')->get(['telegram_id']);

        return response()->json([
            'status' => true,
            'data' => $list
        ]);
    }

    public function getUser(Request $request)
    {
        $credentials = $request->validate([
            'uuid' => ['string', 'required']
        ]);

        $user = Adson::query()->where($credentials)
            ->first(['telegram_id']);

        if ( $user == null ) {
            return response()->json([
                'status' => false
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $user['telegram_id']
        ]);
    }
}
