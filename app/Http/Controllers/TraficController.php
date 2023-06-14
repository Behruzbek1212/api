<?php

namespace App\Http\Controllers;

use App\Http\Resources\TraficResource;
use App\Models\Trafic;
use Illuminate\Http\Request;

class TraficController extends Controller
{
    public function all(Request $request)
    {
        $trafics = Trafic::get();
        $list = TraficResource::collection($trafics);
        return response()->json([
            'status' => true,
            'result' => $list
        ]);
    }
}
