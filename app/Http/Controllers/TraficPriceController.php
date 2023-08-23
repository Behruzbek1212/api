<?php

namespace App\Http\Controllers;

use App\Http\Resources\TraficResource;
use App\Models\Trafic;
use App\Models\TraficPrice;
use Illuminate\Http\Request;

class TraficPriceController extends Controller
{
    public function all(Request $request)
    {
        $trafics = TraficPrice::get();
        // $list = TraficResource::collection($trafics);
        return response()->json([
            'status' => true,
            'result' => $trafics
        ]);
    }
}
