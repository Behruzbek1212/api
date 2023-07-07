<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatCandidateResource;
use App\Http\Resources\ChatCustomerResource;
use App\Models\Chat\Chat;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    use ApiResponse;
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
                $user->candidate->chats()
                    ->with(['resume', 'customer'])
                    ->orderBy('updated_at', 'desc')
                    ->get(),

            'customer' =>
                $user->customer->chats()
                    ->with(['resume', 'candidate'])
                    ->orderBy('updated_at', 'desc')
                    ->get(),

            default => null
        };
        if($user->role == 'customer'){
        
            $data = ChatCustomerResource::collection($chats);
        }
        if($user->role == 'candidate') {
            $data = ChatCandidateResource::collection($chats);
        }
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
    
     


    public function listAll()
    {
        /** @var Authenticatable|User $user */
        $user = _auth()->user();

        $chats = match ($user->role) {
            'candidate' =>
                $user->candidate->chats()
                    ->with(['resume'])
                    ->where('deleted_at', null)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(request()->get('limit') ?? 10),

            'customer' =>
                $user->customer->chats()
                    ->with(['job'])
                    ->orderBy('updated_at', 'desc')
                    ->paginate(request()->get('limit') ?? 10),

            default => null
        };

      
        if($user->role == 'customer'){
            $data = ChatCustomerResource::collection($chats);
        }
        
        if($user->role == 'candidate') {
            $data = ChatCandidateResource::collection($chats);
        }
       
        return $this->successPaginate($data);
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
                $user->candidate->chats()
                    ->withExists(['resume', 'customer'])
                    ->with('messages')
                    ->findOrFail($id),

            'customer' =>
                $user->customer->chats()
                    ->withExists(['resume', 'candidate'])
                    ->with('messages')
                    ->findOrFail($id),

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
