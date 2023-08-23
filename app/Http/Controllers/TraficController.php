<?php

namespace App\Http\Controllers;

use App\Http\Resources\TraficResource;
use App\Models\Trafic;
use Illuminate\Http\Request;

class TraficController extends Controller
{
    public function allSite(Request $request)
    {
        $trafics = Trafic::where('key', Trafic::KEY_FOR_SITE)->get();
        $list = TraficResource::collection($trafics);
        return response()->json([
            'status' => true,
            'result' => $list
        ]);
    }

    public function allTelegram(Request $request)
    {
        $trafics = Trafic::where('key',Trafic::KEY_FOR_TELEGRAM)->get();
        $list = TraficResource::collection($trafics);
        return response()->json([
            'status' => true,
            'result' => $list
        ]);
    }
}
