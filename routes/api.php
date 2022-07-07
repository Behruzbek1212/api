<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
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

Route::get('/', HomeController::class);

Route::prefix('/v1')->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::prefix('/auth')->name('auth.')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::post('/register', [RegisterController::class, 'register'])->name('register');
            Route::post('/login', [RegisterController::class, 'register'])->name('login');
            Route::post('/restore', [RegisterController::class, 'register'])->name('restore');
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [RegisterController::class, 'register'])->name('logout');
        });
    });
});
