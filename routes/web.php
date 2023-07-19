<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'welcome';
});

Route::any('/handle/{paysys}', function ($paysys) {
    return response()->json((new Goodoneuz\PayUz\PayUz)->driver($paysys)->handle());
});
