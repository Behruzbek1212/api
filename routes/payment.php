<?php

use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\PayzeController;
use Illuminate\Support\Facades\Route;
use PayzeIO\LaravelPayze\Payze;

/*
|--------------------------------------------------------------------------
| PAYMENT Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', [PaymentController::class, 'index']);

Route::prefix('handle')->group(function () {
    Payze::routes(PayzeController::class);

    Route::match(
        ['GET', 'POST'],
        '{pay_system}',
        [PaymentController::class, 'handle']
    );
});

Route::prefix('pay')->group(function () {
    Route::get('{pay_system}/{id}/{amount}/{currency?}', [PaymentController::class, 'redirect']);
});
