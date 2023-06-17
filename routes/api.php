<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RestoreController;
use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DeleteDataController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\TestUserController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\ChangeRoleController;
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
Route::post('/bitrix', [BitrixController::class, 'index'])->name('index');
Route::prefix('/v1')->group(function () {

    // User | Me ------------------------------------
    Route::get('/me', [Controller::class, 'user'])
        ->middleware('auth:sanctum');

    // Authorization --------------------------------
    Route::prefix('/auth')->name('auth.')->group(function () {
        Route::middleware('guest:sanctum')->group(function () {
            Route::post('/register', [RegisterController::class, 'register'])->name('register');
            Route::post('/login', [LoginController::class, 'login'])->name('login');


            Route::prefix('/check')->name('check.')->group(function () {
                Route::post('/', [VerificationController::class, 'check'])->name('index');
                Route::post('/verify', [VerificationController::class, 'verify'])->name('verify');
            });

            Route::prefix('/restore')->name('restore.')->group(function () {
                Route::post('/send', [RestoreController::class, 'send'])->name('send');
                Route::post('/verify', [RestoreController::class, 'verify'])->name('verify');
                Route::post('/change', [RestoreController::class, 'restore'])->name('change');
            });
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
            Route::post('/role', [RegisterController::class, 'role'])->name('role');
        });
    });
    // Route::get('/resume/show/{id}', [ResumeController::class, 'show']);

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
        Route::get('/similar_jobs', [JobController::class, 'similar_jobs'])->name('similar_jobs');
        Route::get('/customer_releted_jobs/{id}', [JobController::class, 'customer_releted_jobs'])->name('customer_releted_jobs');
        Route::get('/get/{slug}', [JobController::class, 'get'])->name('get');
        Route::post('/respond', [JobController::class, 'respond'])->name('respond');

        Route::middleware(['auth:sanctum', 'is_customer'])->group(function () {
            Route::post('/create', [JobController::class, 'create'])->name('create');
            Route::post('/edit/{slug}', [JobController::class, 'edit'])->name('edit');
            Route::post('/destroy/{slug}', [JobController::class, 'destroy'])->name('destroy');
            Route::post('/acceptance', [JobController::class, 'acceptance'])->name('acceptance');
            Route::post('/{slug}/applications', [JobController::class, 'applications'])->name('applications');
        });
    });

    // Candidates -----------------------------------------
    Route::prefix('/candidates')->name('candidates.')->group(function () {
        Route::get('/', [CandidatesController::class, 'all'])->name('all');
        Route::get('/get/{id}', [CandidatesController::class, 'get'])->name('get');
        Route::post('/respond', [CandidatesController::class, 'respond'])->middleware(['auth:sanctum', 'is_customer'])->name('respond');
        Route::post('/add-test', [CandidatesController::class, 'addTestResult'])->name('add-test-result');
    });

    // Companies -----------------------------------------
    Route::prefix('/companies')->name('companies.')->group(function () {
        Route::get('/', [CompaniesController::class, 'all'])->name('all');
        Route::get('/get/{id}', [CompaniesController::class, 'get'])->name('get');
        Route::get('/job', [CompaniesController::class, 'job'])->name('job');
    });
    // Locations -----------------------------------------
    Route::prefix('/location')->group(function () {
        Route::get('/', [LocationController::class, 'all']);
        Route::get('/get/{id}', [LocationController::class, 'get']);
        Route::get('/region', [LocationController::class, 'region']);
        Route::get('/add', [LocationController::class, 'add']);
    });
    // Categories -----------------------------------------
    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', [CategoriesController::class, 'index'])->name('index');
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

        // Chat -----------------------------------------
        Route::prefix('/chats')->name('chats.')->group(function () {
            Route::post('/', [ChatsController::class, 'list'])->name('index');
            Route::post('/{id}', [ChatsController::class, 'get'])->name('get');

            Route::post('/{id}/send', [ChatsController::class, 'send'])->name('send');
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
            Route::post('/make', [ResumeController::class, 'store'])->name('make');
            Route::post('/edit', [ResumeController::class, 'update'])->name('make');
            Route::post('/get/{id}', [ResumeController::class, 'get'])->name('get');
            Route::post('/remove/{id}', [ResumeController::class, 'destroy'])->name('remove');
        });

        // User ---------------------------------------
        Route::prefix('/settings')->name('settings.')->group(function () {
            Route::post('/change-role', [ChangeRoleController::class, 'update'])->name('change-role');
            Route::post('/update-data', [ChangeRoleController::class, 'updateData'])->name('update-data');
            // Route::post('/change-candidate-services', [ChangeRoleController::class, 'updateCandidateServicesData'])->name('change-candidate-services');
            Route::post('/change-password', [ChangePasswordController::class, 'change'])->name('change-password');
        });
    });

    // Resume Display|Download ---------------------------------------
    Route::get('resume/show/{id}', [ResumeController::class, 'show'])->name('resume.show');
    Route::get('resume/download/{id}', [ResumeController::class, 'download'])->name('resume.download');

    Route::prefix('/test-user')->name('test-user.')->group(function () {
        Route::get('/', [TestUserController::class, 'index'])->name('index');
        Route::get('/check-status', [TestUserController::class, 'checkStatus'])->name('checkStatus');
        Route::post('/register', [TestUserController::class, 'register'])->name('register');
        Route::post('/login', [TestUserController::class, 'login'])->name('login');
        Route::middleware(['auth:sanctum', 'is_customer'])->group(function () {
            Route::get('/list', [TestUserController::class, 'list'])->name('list');
        });
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [TestUserController::class, 'me'])->name('me');
            Route::post('/add-test', [TestUserController::class, 'addTestResult'])->name('addTestResult');
        });
        //       Route::get('/me', [TestUserController::class, 'loginWithToken'])->name('login-with-token');
    });


    Route::prefix('/utils')->name('utils.')->group(function () {
        Route::post('upload', [UploadController::class, 'upload'])->name('upload');
    });

    Route::prefix('/admin')->middleware('is_admin')->name('admin.')->group(function () {
        require_once __DIR__ . '/admin.php';
    });
});
