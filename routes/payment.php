<?php

use App\Models\User;
use App\Services\Payment;
use App\Services\Payment\PaymentException;
use Illuminate\Support\Facades\Route;

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

Route::prefix('handle')->group(function () {
    Route::match(['GET', 'POST'], '{pay_system}', function ($pay_system) {
        return (new Payment)
            ->driver($pay_system)
            ->handle();
    });
});

Route::prefix('pay')->group(function () {
    Route::get('{pay_system}/{id}/{amount}', function ($pay_system, $id, $amount) {
        $model = User::query()->find($id);

        if (! $model)
            throw new PaymentException('Model not found.');

        return (new Payment)
            ->driver($pay_system)
            ->redirect($model, $amount);
    });
});
