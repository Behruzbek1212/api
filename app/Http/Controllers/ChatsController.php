<?php

namespace App\Http\Controllers;

use App\Models\Chat\Chat;
use App\Models\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    /**
     * Get chats list
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        /** @var Authenticatable|User $user */
        $user = _auth()->user();

        $chats = match ($user->role) {
            'candidate' =>
                $user->candidate->chats,

            'customer' =>
                $user->customer->chats,

            default => null
        };

        return response()->json([
            'status' => true,
            'data' => $chats
        ]);
    }

    /**
     * Get chat
     *
     * @param int $id
     * @return JsonResponse
     */
    public function get(int $id): JsonResponse
    {
        /** @var Authenticatable|User $user */
        $user = _auth()->user();

        $chat = match ($user->role) {
            'candidate' =>
                $user->candidate->chats()->with('messages')->findOrFail($id),

            'customer' =>
                $user->customer->chats()->with('messages')->findOrFail($id),

            default => null
        };

        return response()->json([
            'status' => true,
            'data' => $chat
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function send(Request $request, int $id): JsonResponse
    {
        $params = $request->validate([
            'message' => ['required', 'string']
        ]);

        $chat = Chat::query()->findOrFail($id);
        $chat->messages()->create([
            'message' => $params['message'],
            'role' => $request->user()->role
        ]);

        return response()->json([
            'status' => true
        ]);
    }
}
