<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use SimpleSoftwareIO\QrCode\Generator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

if (! function_exists('_auth')) {
    /**
     * Get the available auth instance.
     *
     * @param string $guard
     * @return Factory|Guard|StatefulGuard
     */
    function _auth(string $guard = 'sanctum'): Guard|StatefulGuard|Factory
    {
        return auth($guard);
    }
}

if (! function_exists('_user')) {
    /**
     * Get current user information.
     *
     * @return Authenticatable|User|null
     */
    function _user(): Authenticatable|User|null
    {
        return _auth()->user();
    }
}

if (! function_exists('qrcode')) {
    /**
     * Generate Qr-code facade helper
     *
     * @return Generator
     */
    function qrcode(int $size): Generator
    {
        return QrCode::size($size);
    }
}
