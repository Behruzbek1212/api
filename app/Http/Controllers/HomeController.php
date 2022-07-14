<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return JsonResponse
     */
    public function __invoke()
    {
        return response()->json([
            'version' => 'v' . env('APP_VERSION', '1.0.0'),
            'development' => 'infoshop',
            'repo' => 'https://github.com/jobo-uz/',
        ]);
    }

    /**
     * Handle the incoming request.
     *
     * @return JsonResponse
     */
    public function fallback()
    {
        return response()->json([
            'status' => 404,
            'version' => 'v' . env('APP_VERSION', '1.0.0'),
            'development' => 'infoshop',
            'repo' => 'https://github.com/jobo-uz/',
        ], 404);
    }
}
