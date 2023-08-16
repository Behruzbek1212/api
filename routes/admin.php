<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CandidatesController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CompaniesController;
use App\Http\Controllers\Admin\GuidesController;
use App\Http\Controllers\Admin\HistoryAdminController;
use App\Http\Controllers\Admin\JobsController;
use App\Http\Controllers\Admin\LimitController;
use App\Http\Controllers\Admin\StatisticAdminController;
use App\Http\Controllers\Admin\TraficController;
use App\Http\Controllers\Admin\ResumeController;
use App\Http\Controllers\Admin\ResumeBallController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobsController::class, 'index']);
    Route::get('/{slug}', [JobsController::class, 'show']);
    Route::post('/create', [JobsController::class, 'create']);
    Route::post('/edit', [JobsController::class, 'edit']);
    Route::post('/destroy', [JobsController::class, 'destroy']);
});

Route::prefix('/trafics')->name('trafics.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\TraficController::class, 'index']);
    Route::get('/{slug}', [App\Http\Controllers\Admin\TraficController::class, 'show']);
    Route::post('/create', [App\Http\Controllers\Admin\TraficController::class, 'create']);
    Route::post('/edit', [App\Http\Controllers\Admin\TraficController::class, 'edit']);
    Route::post('/destroy', [App\Http\Controllers\Admin\TraficController::class, 'destroy']);
});

Route::prefix('/limits')->name('limits.')->group(function () {
    Route::get('/', [LimitController::class, 'index']);
    Route::get('/{slug}', [LimitController::class, 'show']);
    Route::post('/create', [LimitController::class, 'create']);
    Route::post('/edit', [LimitController::class, 'edit']);
    Route::post('/destroy', [LimitController::class, 'destroy']);
});

Route::prefix('/candidates')->name('candidates.')->group(function () {
    Route::get('/', [CandidatesController::class, 'index']);
    Route::get('/{slug}', [CandidatesController::class, 'show']);
    Route::post('/create', [CandidatesController::class, 'create']);
    Route::post('/edit', [CandidatesController::class, 'edit']);
    Route::post('/destroy', [CandidatesController::class, 'destroy']);
    Route::post('/change-services', [CandidatesController::class, 'updateServices']);
});

Route::prefix('/companies')->name('candidates.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\CompaniesController::class, 'index']);
    Route::get('/jobs', [App\Http\Controllers\Admin\CompaniesController::class, 'companiesJobs']);
    Route::get('/{id}', [App\Http\Controllers\Admin\CompaniesController::class, 'show']);

    Route::post('/create', [App\Http\Controllers\Admin\CompaniesController::class, 'create']);
    Route::post('/edit', [App\Http\Controllers\Admin\CompaniesController::class, 'edit']);
    Route::post('/destroy', [App\Http\Controllers\Admin\CompaniesController::class, 'destroy']);
    Route::post('/add-service', [App\Http\Controllers\Admin\CompaniesController::class, 'addServices']);
});

Route::prefix('/users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/create', [UserController::class, 'create']);
    Route::post('/edit', [UserController::class, 'edit']);
    Route::post('/destroy', [UserController::class, 'destroy']);
});

Route::prefix('/guides')->name('guides.')->group(function () {
    Route::get('/', [GuidesController::class, 'index']);
    Route::get('/{slug}', [GuidesController::class, 'show']);
    Route::post('/create', [GuidesController::class, 'create']);
    Route::post('/edit', [GuidesController::class, 'edit']);
    Route::post('/destroy', [GuidesController::class, 'destroy']);
});

Route::prefix('/statistic')->name('statistic.')->group(function () {
    Route::get('/', [StatisticAdminController::class, 'getStatis'])->name('all');
    Route::post('/customer', [StatisticAdminController::class, 'getCustomer'])->name('customer');
    Route::post('/candidate', [StatisticAdminController::class, 'getCandidates'])->name('candidate');
    Route::post('/vacancies', [StatisticAdminController::class, 'getVacancies'])->name('vacancies');
});

Route::prefix('/resume')->name('resume.')->group(function () {
    Route::get('/', [ResumeController::class, 'index']);
    Route::post('/store', [ResumeController::class, 'store']);
    Route::get('/show/{id}', [ResumeController::class, 'show']);
    Route::post('/edit', [ResumeController::class, 'update']);
    Route::post('/destroy', [ResumeController::class, 'destroy']);
});
Route::prefix('/resume-ball')->name('resume-ball.')->group(function () {
    Route::get('/', [ResumeBallController::class, 'index']);
    Route::post('/store', [ResumeBallController::class, 'store']);
    Route::get('/show/{resumeBall}', [ResumeBallController::class, 'show']);
    Route::post('/edit', [ResumeBallController::class, 'update']);
    Route::delete('/destroy/{id}', [ResumeBallController::class, 'destroy']);
});

Route::prefix('/announcement')->name('announcement.')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index']);
    Route::post('/store', [AnnouncementController::class, 'store']);
    Route::post('/confirmation', [AnnouncementController::class,'confirmation']);
    Route::get('/show/{id}', [AnnouncementController::class, 'show']);
    Route::post('/edit', [AnnouncementController::class, 'update']);
    Route::post('/destroy', [AnnouncementController::class, 'destroy']);
});

Route::prefix('/comment')->name('comment.')->group(function () {
    Route::post('/store', [CommentController::class, 'store']);
    Route::post('/show', [CommentController::class, 'getComment']);
});


Route::prefix('/history')->name('history.')->group(function() {
    Route::get('/hr', [HistoryAdminController::class, 'getHistoryHr']);
});
