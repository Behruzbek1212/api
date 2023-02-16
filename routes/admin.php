<?php

use App\Http\Controllers\Admin\JobsController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobsController::class, 'index']);
    Route::get('/{slug}', [JobsController::class, 'show']);
    Route::post('/create', [JobsController::class, 'create']);
    Route::post('/edit', [JobsController::class, 'edit']);
    Route::post('/destroy', [JobsController::class, 'destroy']);
});
