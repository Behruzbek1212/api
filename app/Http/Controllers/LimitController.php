<?php

namespace App\Http\Controllers;

use App\Http\Resources\LimitResource;
use App\Models\LimitModel;
use Illuminate\Http\Request;

class LimitController extends Controller
{
    public function all(Request $request)
    {
        $limits = LimitModel::get();
        $list = LimitResource::collection($limits);
        return response()->json([
            'status' => true,
            'result' => $list
        ]);
    }
}
