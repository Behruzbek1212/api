<?php

use App\Http\Controllers\Admin\JobsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JobsController::class, 'index']);
