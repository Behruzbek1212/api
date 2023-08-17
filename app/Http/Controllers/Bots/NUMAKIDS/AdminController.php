<?php

namespace App\Http\Controllers\Bots\NUMAKIDS;

use App\Http\Controllers\Controller;
use App\Models\Bot\Numakids;
use App\Models\Bot\NumakidsCrater;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addLinks(Request $request)
    {
        $credentials = $request->validate([
            'identification' => ['string', 'required'],
            'url' => ['string', 'required'],
            'image' => ['string', 'required']
        ]);

        NumakidsCrater::query()->updateOrCreate([
            'identification' => $credentials['identification']
        ], $credentials);

        return response()->json([
            'status' => true,
        ]);
    }

    public function getUsers()
    {
        $list = Numakids::query()->distinct('telegram_id')->get(['telegram_id']);

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

        $user = Numakids::query()->where($credentials)
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
