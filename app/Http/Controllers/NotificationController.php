<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    protected $user;

    /**
     * Notification controller constructor
     *
     * @return void
     */
    public function __construct()
    {
       $this->user = _auth()->user();
    }

    /**
     * Get the notifications
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $role = $this->user->role;
        $data = $this->user->notifications()->where('data->role', $role)->orderBy('created_at', 'desc')->paginate($request->limit ?? 25);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Mark as read notification
     *
     * @param Request $request
     * @param string|integer $id
     * @return JsonResponse
     */
    public function read(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|string'
        ]);
        $notification = $this->user->notifications()->where('id', $request->notification_id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'status' => true,
                'message' => 'Successfully read notification'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Not found'
        ]);
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
    public function destroy(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|string'
        ]);
        $notification = $this->user->notifications()->where('id', $request->notification_id)->first();

        if ($notification) {
            DB::table('notifications')->where('id', $notification->id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Successfully deleted notification'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Not found'
        ]);
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
