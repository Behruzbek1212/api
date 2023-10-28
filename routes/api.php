<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RestoreController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BotLoginController;
use App\Http\Controllers\CalledInterviewCustomerController;
use App\Http\Controllers\CandidateExamController;
use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\CheckEmailController;
use App\Http\Controllers\CheckPhoneController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerChatCommentController;
use App\Http\Controllers\CustomerStatusController;
use App\Http\Controllers\DeleteDataController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\Exam\ExamQuestionController;
use App\Http\Controllers\Exam\ExamUserController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GoldenNitController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LimitController;
use App\Http\Controllers\LanguageLevelsController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResumeBallsController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SelectedQuestionController;
use App\Http\Controllers\SocialStatusController;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\TestUserController;
use App\Http\Controllers\TraficController;
use App\Http\Controllers\TraficPriceController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\ChangeRoleController;
use App\Http\Controllers\Utils\UploadController;
use App\Http\Controllers\WishlistController;
use App\Models\CandidateExam;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use PHPUnit\Logging\TestDox\TestResultCollector;

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

// Route::get('/', HomeController::class);
// Route::fallback([HomeController::class, 'fallback']);
Route::post('/bitrix', [BitrixController::class, 'index'])->name('index');
Route::get('/cron_jobs', [JobController::class, 'cron_jobs'])->name('cron_jobs');
Route::get('/cron_backup', [BackupController::class, 'backup'])->name('cron_backup');

Route::prefix('/v1')->group(function () {
    Route::get('/block-number', function () {
        $data = [
            "refresh" => 0,
            "items" => [
                ["number" => "001", "name" => "", "firstname" => "", "lastname" => "", "phone" => "947980058", "mobile" => "947980058", "email" => "", "address" => "", "city" => "", "state" => "", "zip" => "", "comment" => "", "presence" => 0, "starred" => 0, "info" => ""],
                ["number" => "002", "name" => "", "firstname" => "", "lastname" => "", "phone" => "+998947980058", "mobile" => "+998947980058", "email" => "", "address" => "", "city" => "", "state" => "", "zip" => "", "comment" => "", "presence" => 0, "starred" => 0, "info" => ""],
                ["number" => "003", "name" => "", "firstname" => "", "lastname" => "", "phone" => "+998993960990", "mobile" => "+998993960990", "email" => "", "address" => "", "city" => "", "state" => "", "zip" => "", "comment" => "", "presence" => 0, "starred" => 0, "info" => ""],
                ["number" => "004", "name" => "", "firstname" => "", "lastname" => "", "phone" => "993960990", "mobile" => "993960990", "email" => "", "address" => "", "city" => "", "state" => "", "zip" => "", "comment" => "", "presence" => 0, "starred" => 0, "info" => ""],
            ]
        ];

        return response()->json($data);
    });

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

    // payme -----------------------------------------
    // Route::prefix('/payme')->name('payme.')->group(function () {
    //     Route::any('/handle/{paysys}', function ($paysys) {
    //         return response()->json((new Goodoneuz\PayUz\PayUz)->driver($paysys)->handle());
    //     });
    // });
    // Jobs -----------------------------------------
    Route::prefix('/jobs')->name('jobs.')->group(function () {

        Route::get('/', [JobController::class, 'all'])->name('all');
        Route::get('/all_jobs', [JobController::class, 'all_jobs'])->name('all_jobs');
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

        //questions
        Route::middleware(['auth:sanctum', 'is_customer'])->group(function () {
            Route::get('/questions', [QuestionController::class, 'all'])->name('all');
            Route::post('/question_create', [QuestionController::class, 'create'])->name('create');
            Route::post('/edit/{slug}', [JobController::class, 'edit'])->name('edit');
            Route::post('/destroy/{slug}', [JobController::class, 'destroy'])->name('destroy');

        });

        // limits ---------------------------------------

    });

    // Route::prefix('customer')->name('customer.')->middleware(['auth:sanctum', 'is_customer'])->group(function () {

    //     // Route::post('/create',[CustomerStatusController::class, 'create'])->name('create');
    //     Route::post('/update-status', [CustomerStatusController::class, 'updatedCandidateStatus'])->name('update-status');
    // });

    Route::prefix('customer-status')->name('customer-status.')->middleware(['auth:sanctum', 'is_customer'])->group(function () {
        Route::post('/update-status', [CustomerStatusController::class, 'updatedCandidateStatus'])->name('customer-status');
    });

    // Candidates -----------------------------------------
    Route::prefix('/candidates')->name('candidates.')->group(function () {
        Route::get('/', [CandidatesController::class, 'all'])->name('all');
        // limit all candidate
        Route::get('/candidates', [CandidatesController::class, 'candidates'])->name('candidates');

        Route::get('/get/{id}', [CandidatesController::class, 'get'])->name('get');
        // onclick candidate
        Route::get('/get_one/{id}', [CandidatesController::class, 'get_one'])->name('get_one');
        //limit get one candidate
        Route::get('/get_one_candidate/{id}', [CandidatesController::class, 'get_one_candidate'])->name('get_one_candidate');
        Route::post('/respond', [CandidatesController::class, 'respond'])->middleware(['auth:sanctum', 'is_customer'])->name('respond');
        Route::post('/add-test', [CandidatesController::class, 'addTestResult'])->name('add-test-result');
        Route::post('/telegram-id', [CandidatesController::class, 'createTelegram']);
    });

    Route::prefix('/limits')->name('limits.')->group(function () {
        Route::get('/', [LimitController::class, 'all'])->name('limits');
    });
    // Trafics -----------------------------------------
    Route::prefix('/trafics')->name('trafics.')->group(function () {
        Route::get('/', [TraficController::class, 'allSite'])->name('allSite');
        Route::get('/telegram', [TraficController::class, 'allTelegram'])->name('allTelegram');
    });

    // trafic_price -----------------------------------------
    Route::prefix('/trafic_price')->name('trafic_price.')->group(function () {
        Route::get('/', [TraficPriceController::class, 'all'])->name('all');
    });


    // exams -----------------------------------------
    Route::prefix('/exams')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'all'])->name('all');
        Route::post('add', [ExamController::class, 'add']);
    });

     // exam_questions -----------------------------------------
    Route::group(['prefix' => 'exam-question'], function () {
        Route::get('index', [ExamQuestionController::class, 'index']);
        Route::post('add', [ExamQuestionController::class, 'add']);
        Route::post('finish', [ExamQuestionController::class, 'finish']);
    });

     // exam_user -----------------------------------------
    Route::group(['prefix' => 'exam-user'], function () {
        Route::get('index', [ExamUserController::class, 'index']);
    });


    // exams  for candidate -----------------------------------------
    Route::prefix('/candidate_exams')->name('exams.')->group(function () {
        Route::get('/', [CandidateExamController::class, 'list'])->name('all');
    });

    // exams for customer -----------------------------------------
    Route::middleware(['auth:sanctum', 'is_customer'])->group(function () {
        Route::prefix('/exams')->group(function () {
            Route::post('/create', [CandidateExamController::class, 'create'])->name('create');
        });
    });

    // Companies -----------------------------------------
    Route::prefix('/companies')->name('companies.')->group(function () {
        Route::get('/', [CompaniesController::class, 'all'])->name('all');
        Route::get('/get/{id}', [CompaniesController::class, 'get'])->name('get');
        Route::get('/job', [CompaniesController::class, 'job'])->name('job');
        Route::post('/telegram-id', [CompaniesController::class, 'createTelegram']);
    });

    // Transaction_history -----------------------------------------
    Route::prefix('/transaction_history')->name('transaction_history.')->group(function () {
        Route::post('/create', [TransactionHistoryController::class, 'create'])->name('create');
        Route::get('/', [TransactionHistoryController::class, 'all'])->name('all');
        Route::post('/destroy', [TransactionHistoryController::class, 'destroy'])->name('destroy');
    });

    // questions_for_job -----------------------------------------
    Route::prefix('/job_questions')->name('job_questions.')->group(function () {
        Route::get('/get/{slug}', [SelectedQuestionController::class, 'all'])->name('all');
        Route::post('/destroy/{slug}', [SelectedQuestionController::class, 'destroy'])->name('destroy');
        Route::post('/create', [SelectedQuestionController::class, 'create'])->name('create');
        Route::get('/job_answer/{slug}', [SelectedQuestionController::class, 'job_answer'])->name('job_answer');
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
            Route::post('/read', [NotificationController::class, 'read'])->name('read');
            // Destroy
            Route::post('/destroy/all', [NotificationController::class, 'destroy_all'])->name('destroy.all');
            Route::post('/destroy', [NotificationController::class, 'destroy'])->name('destroy');
        });

        // Chat -----------------------------------------
        Route::prefix('/chats')->name('chats.')->group(function () {
            Route::post('/', [ChatsController::class, 'list'])->name('index');
            Route::get('/messages/{id}', [ChatsController::class, 'getMessage'])->name('getMessage');
            Route::post('/{id}', [ChatsController::class, 'get'])->name('get');
            Route::get('/all', [ChatsController::class, 'listAll']);
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
    // Candidate resume download with test api resume/download/test/{id}
    Route::get('resume/download/test/{id}/customer/{customer_id}', [ResumeController::class , 'downloadtestCus']);
    Route::get('resume/admin/show/{id}', [ResumeController::class, 'showForAdmin'])->name('resume.show');
    Route::get('resume/admin/download/{id}', [ResumeController::class, 'downloadForAdmin'])->name('resume.download');
    Route::get('resume/admin/with-tests/download/{id}', [ResumeController::class, 'downloadForAdminWithTests']);

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


    Route::prefix('interview')->name('interview')->middleware(['auth:sanctum', 'is_customer'])->group(function () {
        Route::get('/', [CalledInterviewCustomerController::class, 'index'])->name('all');
        Route::post('/create', [CalledInterviewCustomerController::class, 'store'])->name('create');
        Route::post('/edit/status', [CalledInterviewCustomerController::class, 'editStatus'])->name('editStatus');
        Route::get('/show', [CalledInterviewCustomerController::class, 'show'])->name('show-candidate');
        Route::get('/show/interview', [CalledInterviewCustomerController::class, 'showInter'])->name('show-inter');
        Route::post('/update-date', [CalledInterviewCustomerController::class, 'update'])->name('update-date');
        Route::post('/destroy', [CalledInterviewCustomerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('bot-login')->name('bot-login')->group(function () {
        Route::post('/create', [BotLoginController::class, 'store'])->name('create');
        Route::post('/update-lang', [BotLoginController::class, 'languageUpdate'])->name('update-lang');
        Route::post('/check', [BotLoginController::class, 'check'])->name('check');
        Route::post('/destroy', [BotLoginController::class, 'destroy'])->middleware(['auth:sanctum'])->name('destroy');
    });


    Route::prefix('/education-level')->name('education-level.')->group(function () {
        Route::get('/', [EducationLevelController::class, 'index'])->name('index');
    });

    Route::prefix('/social-status')->name('social-status.')->group(function () {
        Route::get('/', [SocialStatusController::class, 'index'])->name('index');
    });
    Route::prefix('/language')->name('language.')->group(function () {
        Route::get('/', [LanguagesController::class, 'index'])->name('index');
    });


    Route::prefix('/language-level')->name('language-level.')->group(function () {
        Route::get('/', [LanguageLevelsController::class, 'index'])->name('index');
    });


    // Resume balls
    Route::get('resume-ball', [ResumeBallsController::class, 'getBall'])->name('resume-ball');

    // check email route

    Route::post('email/check', [CheckEmailController::class, 'check']);

    // user delete route

    Route::post('delete/user', [DeleteDataController::class, 'delete'])->middleware('api_token');

    Route::prefix('/utils')->name('utils.')->group(function () {
        Route::post('upload', [UploadController::class, 'upload'])->name('upload');
    });

    Route::prefix('/admin')->middleware('is_admin')->name('admin.')->group(function () {
        require_once __DIR__ . '/admin.php';
    });

    // Bots ------------------------------
    require_once __DIR__ . '/bots.php';
});


Route::prefix('/v2')->group(function () {
    // Locations -----------------------------------------
    Route::prefix('/location')->group(function () {
        Route::get('/region', [LocationController::class, 'regionNull']);
    });

    Route::prefix('/announcement')->name('announcement.')->group(function () {
        Route::get('/', [AnnouncementController::class, 'all']);
        Route::get('/check', [AnnouncementController::class, 'dateCheck']);
        Route::post('/create', [AnnouncementController::class, 'create'])->middleware(['auth:sanctum', 'is_customer']);
        Route::post('/confirmation', [AnnouncementController::class, 'storeConfirmation'])->middleware(['auth:sanctum', 'is_customer']);
        Route::post('/edit', [AnnouncementController::class, 'update'])->middleware(['auth:sanctum', 'is_customer']);
    });

    // Golden nit telegram bot api routes
    Route::prefix('/golden')->group(function () {
        Route::get('/', [GoldenNitController::class, 'index']);
        Route::post('/store', [GoldenNitController::class, 'store']);
    });

    // check phone number
    Route::post('phone/check', [CheckPhoneController::class, 'check']);

    // customer status columns api routes


    // These routes are for commenting on customer chat

    Route::prefix('customer-comment')->name('customerComment.')->middleware(['auth:sanctum', 'is_customer'])->group(function () {
        Route::get('/chat/{id}', [CustomerChatCommentController::class, 'getComment'])->name('all');
        Route::post('/create', [CustomerChatCommentController::class, 'create'])->name('create');
        Route::get('/show/{id}', [CustomerChatCommentController::class, 'show'])->name('show');
        Route::post('/edit', [CustomerChatCommentController::class, 'update'])->name('edit');
        Route::post('/destroy', [CustomerChatCommentController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('test-result')->name('test-result.')->group(function () {

        Route::get('/all', [TestResultController::class, 'getAll'])->middleware(['auth:sanctum', 'is_customer'])->name('all');
        Route::get('/view/all', [TestResultController::class, 'allTestResultCandidate']);
        Route::get('/all', [TestResultController::class,  'getAll'])->middleware(['auth:sanctum', 'is_customer'])->name('all');
        Route::post('/store', [TestResultController::class, 'store'])->name('create');
        Route::get('/downloadOne/{id}', [TestResultController::class, 'downloadTestResult']);
        Route::get("/customer/donwnload/{id}", [TestResultController::class, 'downloadTestCustomer'])->name('customer-download');
        Route::get('/candidate', [TestResultController::class, 'getCandidateTestResult']);
        Route::get('/show', [TestResultController::class, 'show']);
    });
});
