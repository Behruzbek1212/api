<?php

namespace  App\Http\Controllers\Bots\PartyHr;

use App\Http\Controllers\Controller;
use App\Models\PartyCrater;
use App\Models\PartyHr;
use Illuminate\Http\Request;

class PartyAdminController extends Controller
{
    public function addLinks(Request $request)
    {
        $credentials = $request->validate([
            'identification' => ['string', 'required'],
            'url' => ['string', 'required'],
            'image' => ['string', 'required']
        ]);

        PartyCrater::query()->updateOrCreate([
            'identification' => $credentials['identification']
        ], $credentials);

        return response()->json([
            'status' => true,
        ]);
    }

    public function getUsers()
    {
        $list = PartyHr::query()->distinct('telegram_id')->get(['telegram_id']);

        return response()->json([
            'status' => true,
            'data' => $list
        ]);
    }

    public function getAllUsers()
    {
        $list = PartyHr::query()->get();

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

        $user = PartyHr::query()->where($credentials)
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
