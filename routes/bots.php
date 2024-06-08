<?php

use App\Http\Controllers\Bots\ADSON\AdminController as AdsonAdminController;
use App\Http\Controllers\Bots\ADSON\MainController as AdsonController;
use App\Http\Controllers\Bots\Azaly\AzalyController;
use App\Http\Controllers\Bots\Azaly\AzalyHrController;
use App\Http\Controllers\Bots\Buday\BudayComController;
use App\Http\Controllers\Bots\Buday\BudayComHrController;
use App\Http\Controllers\Bots\MehriGiyo\MehriGiyoController;
use App\Http\Controllers\Bots\MehriGiyo\MehriGiyoHrController;
use App\Http\Controllers\Bots\NUMAKIDS\AdminController as NumakidsAdminController;
use App\Http\Controllers\Bots\NUMAKIDS\MainController as NumakidsController;
use App\Http\Controllers\Bots\PartyHr\PartyAdminController;
use App\Http\Controllers\Bots\PartyHr\PartyHrController;
use App\Http\Controllers\Bots\PORTRETHR\PortretHrController;
use App\Http\Controllers\Bots\PORTRETHR\PortretUserController;
use App\Http\Controllers\Bots\Yalpiz\YalpizComController;
use App\Http\Controllers\Bots\Yalpiz\YalpizComCraterController;
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
        Route::get('get-all-users', [PartyAdminController::class, 'getAllUsers'])->name('getAllUsers');
        Route::post('get-user', [PartyAdminController::class, 'getUser'])->name('get-user');
    });
});


Route::prefix('/_utils/portret-hr')->name('portret-hr.')->group(function () {
    Route::post('store-data' , [PortretHrController::class, 'store'])->name('data-store');
    Route::post('store-file' , [PortretHrController::class, 'createFile'])->name('data-file');
    Route::get('data/{token}', [PortretHrController::class, 'showData'])->name('show-data');
    Route::post('user/store', [PortretUserController::class, 'store'])->name('user-store');
    Route::get('user/get/{token}', [PortretUserController::class, 'showData'])->name('show-user');
    Route::get('user/all', [PortretUserController::class, 'index'])->name('show-All');
    Route::get('user/count', [PortretUserController::class, 'userCount'])->name('user-count');
});


Route::prefix('/_utils/mehri-giyo')->name('mehri-giyo.')->group(function () {
    Route::post('store-data' , [MehriGiyoHrController::class, 'store'])->name('data-store');
    Route::post('store-file' , [MehriGiyoHrController::class, 'createFile'])->name('data-file');
    Route::get('data/{token}', [MehriGiyoHrController::class, 'showData'])->name('show-data');
    Route::post('user/store', [MehriGiyoController::class, 'store'])->name('user-store');
    Route::get('user/get/{token}', [MehriGiyoController::class, 'showData'])->name('show-user');
    Route::get('user/all', [MehriGiyoController::class, 'index'])->name('show-All');
    Route::get('user/count', [MehriGiyoController::class, 'userCount'])->name('user-count');
});

Route::prefix('/_utils/yalpiz')->name('yalpiz.')->group(function () {
    Route::post('store-data' , [YalpizComCraterController::class, 'store'])->name('data-store');
    Route::post('store-file' , [YalpizComCraterController::class, 'createFile'])->name('data-file');
    Route::get('data/{token}', [YalpizComCraterController::class, 'showData'])->name('show-data');
    Route::post('user/store', [YalpizComController::class, 'store'])->name('user-store');
    Route::get('user/get/{token}', [YalpizComController::class, 'showData'])->name('show-user');
    Route::get('user/all', [YalpizComController::class, 'index'])->name('show-All');
    Route::get('user/count', [YalpizComController::class, 'userCount'])->name('user-count');
});

Route::prefix('/_utils/buday')->name('buday.')->group(function () {
    Route::post('store-data' , [BudayComHrController::class, 'store'])->name('data-store');
    Route::post('store-file' , [BudayComHrController::class, 'createFile'])->name('data-file');
    Route::get('data/{token}', [BudayComHrController::class, 'showData'])->name('show-data');
    Route::post('user/store', [BudayComController::class, 'store'])->name('user-store');
    Route::get('user/get/{token}', [BudayComController::class, 'showData'])->name('show-user');
    Route::get('user/all', [BudayComController::class, 'index'])->name('show-All');
    Route::get('user/count', [BudayComController::class, 'userCount'])->name('user-count');
});

Route::prefix('/_utils/azaly')->name('azaly.')->group(function () {
    Route::post('store-data' , [AzalyHrController::class, 'store'])->name('data-store');
    Route::post('store-file' , [AzalyHrController::class, 'createFile'])->name('data-file');
    Route::get('data/{token}', [AzalyHrController::class, 'showData'])->name('show-data');
    Route::post('user/store', [AzalyController::class, 'store'])->name('user-store');
    Route::get('user/get/{token}', [AzalyController::class, 'showData'])->name('show-user');
    Route::get('user/all', [AzalyController::class, 'index'])->name('show-All');
    Route::get('user/count', [AzalyController::class, 'userCount'])->name('user-count');
});
