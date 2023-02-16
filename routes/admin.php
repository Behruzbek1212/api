<?php

use App\Http\Controllers\Admin\CandidatesController;
use App\Http\Controllers\Admin\JobsController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobsController::class, 'index']);
    Route::get('/{slug}', [JobsController::class, 'show']);
    Route::post('/create', [JobsController::class, 'create']);
    Route::post('/edit', [JobsController::class, 'edit']);
    Route::post('/destroy', [JobsController::class, 'destroy']);
});

Route::prefix('/candidates')->name('candidates.')->group(function () {
    Route::get('/', [CandidatesController::class, 'index']);
    Route::get('/{slug}', [CandidatesController::class, 'show']);
    Route::post('/create', [CandidatesController::class, 'create']);
    Route::post('/edit', [CandidatesController::class, 'edit']);
    Route::post('/destroy', [CandidatesController::class, 'destroy']);
});
