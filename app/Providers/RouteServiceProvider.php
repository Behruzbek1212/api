<?php

namespace App\Providers;

use App\Models\PasswordReset;
use App\Models\PasswordVerification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->domain(config('app.domain.api'))
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->domain(config('app.domain.web'))
                ->group(base_path('routes/web.php'));
            Route::middleware('api')
                ->domain(config('payment.domain'))
                ->name('payment.')
                ->group(base_path('routes/payment.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('guest_api', function (Request $request) {
            $phoneNumber = $request->input('phone');
            $ipAddress = $request->ip();

            $verificationExists = PasswordVerification::where('phone', $phoneNumber)->exists();
            $resetExists = PasswordReset::where('phone', $phoneNumber)->exists();

            if ($verificationExists || $resetExists) {
                return Limit::perDay(3)->by($phoneNumber);
            }

            return Limit::perDay(3)->by($ipAddress);
        });
        
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
