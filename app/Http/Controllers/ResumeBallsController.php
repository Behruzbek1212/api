<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResumeBallResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumeBallsController extends Controller
{
    public function  getBall():JsonResponse
    {
        $resumBalls = DB::table('resume_balls')->get();
        $data = ResumeBallResource::collection($resumBalls);
         return response()->json([
             'status' => true,
             'data' => $data
         ]);
    }
}
