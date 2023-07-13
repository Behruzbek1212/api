<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use SimpleSoftwareIO\QrCode\Generator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

if (!function_exists('_auth')) {
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

function formatDateTime($date_string, $format = 'd.m.Y H:i')
{
    if ($date_string == null || $date_string == '') {
        return '';
    }
    return date($format, strtotime($date_string));
}

if (!function_exists('_user')) {
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

if (!function_exists('qrcode')) {
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

function getRequest($request = null)
{
    return $request ?? request();
}

function requestOrder(): array
{
    $order = request()->get('order', '-id');
    if ($order[0] == '-') {
        $result = [
            'key' => substr($order, 1),
            'value' => 'desc',
        ];
    } else {
        $result = [
            'key' => $order,
            'value' => 'asc',
        ];
    }
    return $result;
}


function uploadFile($file, $path, $old = null): ?string
{

    $result = null;
    deleteFile($old);
    if ($file != null) {
        $model = $file->getClientOriginalName();
        $file->storeAs("public/$path", $model);
        $result = "/storage/$path/" . $model;
    }
    return $result;
}

function deleteFile($path): void
{
    if ($path != null && file_exists(public_path() . $path)) {
        unlink(public_path() . $path);
    }
}

function user()
{
    return _auth()->user();
}
