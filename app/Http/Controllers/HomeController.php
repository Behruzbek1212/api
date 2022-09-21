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
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'version' => 'v' . config('app.version'),
            'development' => 'ADON A.K.A Infoshop',
            'repo' => 'https://github.com/jobo-uz/'
        ]);
    }

    /**
     * Handle the incoming request.
     *
     * @return JsonResponse
     */
    public function fallback(): JsonResponse
    {
        return response()->json([
            'status' => 404,
            'version' => 'v' . config('app.version'),
            'development' => 'infoshop',
            'repo' => 'https://github.com/jobo-uz/',
        ], 404);
    }
}
