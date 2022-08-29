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
            'repo' => 'https://github.com/jobo-uz/',
            'contributors' => [
                [
                    'name' => 'Muhammaddiyor Tohirov <remisero>',
                    'mail' => 'milly@mally.moe',
                    'url' => 'https://github.com/thetakhirov'
                ],
                [
                    'name' => 'Ibrohim Bobojonov',
                    'mail' => 'ibrohim777775@gmail.com',
                    'url' => 'https://github.com/ibrohim777775'
                ]
            ]
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
