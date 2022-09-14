<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RestoreController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\Utils\UploadController;
use App\Http\Controllers\WishlistController;
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
Route::fallback([HomeController::class, 'fallback']);

Route::prefix('/v1')->group(function () {
    // User | Me ------------------------------------
    Route::get('/me', [Controller::class, 'user'])
        ->middleware('auth:sanctum');

    // Authorization --------------------------------
    Route::prefix('/auth')->name('auth.')->group(function () {
        Route::prefix('/check')->name('check.')->group(function () {
            Route::post('/', [VerificationController::class, 'check'])->name('index');
            Route::post('/verify', [VerificationController::class, 'verify'])->name('verify');
        });

        Route::middleware('guest:sanctum')->group(function () {
            Route::post('/register', [RegisterController::class, 'register'])->name('register');
            Route::post('/login', [LoginController::class, 'login'])->name('login');

            Route::prefix('/restore')->name('restore.')->group(function () {
                Route::post('/send', [RestoreController::class, 'send'])->name('send');
                Route::post('/verify', [RestoreController::class, 'verify'])->name('verify');
                Route::post('/change', [RestoreController::class, 'restore'])->name('change');
            });
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
        });
    });

    // Guides ---------------------------------------
    Route::prefix('/guides')->name('guides.')->group(function () {
        Route::get('/', [GuideController::class, 'all'])->name('all');
        Route::get('/get/{slug}', [GuideController::class, 'get'])->name('get');

        // Admin routes | TODO:Building 🏗
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/create', [GuideController::class, 'create'])->name('create');
            Route::post('/edit/{id}', [GuideController::class, 'edit'])->name('edit');
            Route::post('/destroy/{id}', [GuideController::class, 'destroy'])->name('destroy');
        });
    });

    // Jobs -----------------------------------------
    Route::prefix('/jobs')->name('jobs.')->group(function () {
        Route::get('/', [JobController::class, 'all'])->name('all');
        Route::get('/get/{slug}', [JobController::class, 'get'])->name('get');

        // Admin routes | TODO:Building 🏗
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/create', [GuideController::class, 'create'])->name('create');
            Route::post('/edit/{id}', [GuideController::class, 'edit'])->name('edit');
            Route::post('/destroy/{id}', [GuideController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        // Notifications --------------------------------
        Route::prefix('/notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('get');
            // Read as mark
            Route::post('/read/all', [NotificationController::class, 'read_all'])->name('read.all');
            Route::post('/read/{id}', [NotificationController::class, 'read'])->name('read');
            // Destroy
            Route::post('/destroy/all', [NotificationController::class, 'destroy_all'])->name('destroy.all');
            Route::post('/destroy/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        });

        // Wishlist -------------------------------------
        Route::prefix('/wishlist')->name('wishlist.')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('index');
            Route::post('/add', [WishlistController::class, 'store'])->name('set');
            Route::post('/remove', [WishlistController::class, 'destroy'])->name('remove');
        });

        // Resume ---------------------------------------
        Route::prefix('/resume')->name('resume.')->group(function () {
            Route::get('/', [ResumeController::class, 'index'])->name('index');
            Route::get('/{id}', [ResumeController::class, 'show'])->name('index');
            Route::post('/make', [ResumeController::class, 'store'])->name('make');
            Route::post('/remove', [ResumeController::class, 'destroy'])->name('remove');
        });
    });

    Route::prefix('/utils')->name('utils.')->group(function () {
        Route::post('upload', [UploadController::class, 'upload'])->name('upload');

        // TODO: Delete
        Route::get('resume', function (\Illuminate\Http\Request $request) {
            $user = $request->get('user');
            $user = \App\Models\User::query()->find($user);

            return (new \App\Services\ResumeService)
                ->load(compact('user'))
                ->stream();
        });
    });
});
