<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected Authenticatable|User $user;

    /**
     * Notification controller constructor
     *
     * @return void
     */
    public function __construct()
    {
//        $this->user = auth('sanctum')->user();
    }

    /**
     * Get the notifications
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Mark as read notification
     *
     * @param Request $request
     * @param string|integer $id
     * @return JsonResponse
     */
    public function read(Request $request, $id)
    {
        //
    }

    /**
     * Mark as read notifications
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function read_all(Request $request)
    {
        //
    }

    /**
     * Delete notification
     *
     * @param Request $request
     * @param string|integer $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        //
    }

    /**
     * Delete all notifications
     *
     * @param Request $request
     * @param string|integer $id
     * @return JsonResponse
     */
    public function destroy_all(Request $request, $id)
    {
        //
    }
}
