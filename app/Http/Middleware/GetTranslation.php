<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GetTranslation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $language = $request->header('JB-Lang');

        if (in_array($language, config('app.available_locales'))) {
            app()->setLocale($language);
        } else app()->setLocale(config('app.locale'));

        return $next($request);
    }
}
