<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response()->json([
        'version' => 'v1.2.0',
        'development' => 'infoshop',
        'repo' => 'https://github.com/jobo-uz/'
    ]);
});

Route::prefix('/v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::prefix('/auth')->name('auth.')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::post('/register', [RegisterController::class, 'store'])->name('register');
            Route::post('/login', [RegisterController::class, 'store'])->name('login');
            Route::post('/restore', [RegisterController::class, 'store'])->name('restore');
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [RegisterController::class, 'store'])->name('logout');
        });
    });
});
