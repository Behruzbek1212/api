<?php

use App\Http\Controllers\Bots\ADSON\AdminController as AdsonAdminController;
use App\Http\Controllers\Bots\ADSON\MainController as AdsonController;
use App\Http\Controllers\Bots\NUMAKIDS\AdminController as NumakidsAdminController;
use App\Http\Controllers\Bots\NUMAKIDS\MainController as NumakidsController;
use App\Http\Controllers\Bots\PartyHr\PartyAdminController;
use App\Http\Controllers\Bots\PartyHr\PartyHrController;
use Illuminate\Support\Facades\Route;

Route::prefix('/_utils/_bots/_adson-crater')->name('bots.')->group(function () {
    Route::post('store', [AdsonController::class, 'store'])->name('store');
    Route::post('check', [AdsonController::class, 'check'])->name('check');
    Route::post('get-url', [AdsonController::class, 'getUrl'])->name('url-get');
    Route::post('get-info', [AdsonController::class, 'getInfo'])->name('info-get');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('add-links', [AdsonAdminController::class, 'addLinks'])->name('add-links');
        Route::post('get-users', [AdsonAdminController::class, 'getUsers'])->name('get-users');
        Route::post('get-user', [AdsonAdminController::class, 'getUser'])->name('get-user');
    });
});

Route::prefix('/_utils/_bots/_numakids-crater')->name('bots.')->group(function () {
    Route::post('store', [NumakidsController::class, 'store'])->name('store');
    Route::post('check', [NumakidsController::class, 'check'])->name('check');
    Route::post('get-url', [NumakidsController::class, 'getUrl'])->name('url-get');
    Route::post('get-info', [NumakidsController::class, 'getInfo'])->name('info-get');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('add-links', [NumakidsAdminController::class, 'addLinks'])->name('add-links');
        Route::post('get-users', [NumakidsAdminController::class, 'getUsers'])->name('get-users');
        Route::post('get-user', [NumakidsAdminController::class, 'getUser'])->name('get-user');
    });
});


Route::prefix('/_utils/_bots/_party-hr')->name('bots.')->group(function () {
    Route::post('store', [PartyHrController::class, 'store'])->name('store');
    Route::post('check', [PartyHrController::class, 'check'])->name('check');
    Route::post('get-url', [PartyHrController::class, 'getUrl'])->name('url-get');
    Route::post('get-info', [PartyHrController::class, 'getInfo'])->name('info-get');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('add-links', [PartyAdminController::class, 'addLinks'])->name('add-links');
        Route::post('get-users', [PartyAdminController::class, 'getUsers'])->name('get-users');
        Route::post('get-user', [PartyAdminController::class, 'getUser'])->name('get-user');
    });
});
