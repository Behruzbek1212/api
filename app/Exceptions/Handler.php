<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Http;
use Psr\Log\LogLevel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (! config('app.debug'))
                return;

            // https://t.me/uzcsbot
            // Http::withOptions(['verify' => false])->post('https://api.telegram.org/bot1627318447:AAEGOUreAjhByN7l2OUl9gp6q_tjZhRLaE4/sendMessage', [
            // https://t.me/jobouz_bot
            Http::withOptions(['verify' => false])->post('https://api.telegram.org/bot5777417067:AAGvh21OUGVQ7nmSnLbIhzTiZxoyMQMIZKk/sendMessage', [
                'chat_id' => '-1001821241273',
                'text' => "title: " . $e->getMessage() . "\n\nFile: " . $e->getFile() . "\nLine: " . $e->getLine()
            ]);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        });
    }
}
