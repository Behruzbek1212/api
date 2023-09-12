<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Http\Resources\ChatCandidateResource;
use App\Http\Resources\ChatCustomerResource;
use App\Models\Chat\Chat;
use App\Models\Chat\Messages;
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
            $data = ChatCandidateResource::collection($chats);
        }
        if($user->role == 'candidate') {
            $data = ChatCustomerResource::collection($chats);
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
        $start = request()->input('start') ?? null;
        $end = request()->input('end') ??  null;
        $slug =  request()->input('slug') ?? null;
        $status = request()->input('status') ?? null;
        $chats = match ($user->role) {
            'candidate' =>
                $user->candidate->chats()
                    ->with(['resume'])
                    ->where('deleted_at', null)
                    ->orderBy('updated_at', 'desc')
                    ->whereHas('job', function ($query) {
                        return $query->where('deleted_at', null);
                    })
                    ->paginate(request()->get('limit') ?? 10),

            'customer' =>
                $user->customer->chats()
                    ->with(['job'])
                    ->where('deleted_at', null)
                    ->orderBy('updated_at', 'desc')
                    ->whereHas('job', function ($query) {
                        return $query->where('deleted_at', null);
                    })
                    ->when($start && $end, function ($query) use ($start, $end){
                        if($start !== null && $end !== null){
                            $query->whereBetween('created_at', [request()->input('start'), request()->input('end')]);
                        }  
                    })
                    ->when($slug, function ($query) use ($slug) {
                        if($slug !== null){
                            $query->where('job_slug', request()->input('slug')); 
                        }  
                    })
                    ->when($status, function ($query) use ($status) {
                        if($status !== null){
                            $query->where('status', request()->input('status'));
                        }
                    })
                    ->paginate(request()->get('limit') ?? 10),

            default => null
        };

      
        if($user->role == 'customer'){
              $data = ChatCandidateResource::collection($chats);
        }
        
        if($user->role == 'candidate') {
            $data = ChatCustomerResource::collection($chats);
          
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
                    ->with('messages', 'candidate.user')
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
        $message = $chat->messages()->create([
            'message' => $params['message'],
            'role' => $request->user()->role
        ]);
        $resume = $chat->resume()->first() ?? null;
        
        if($request->user()->role  == 'customer'){
            event(new SendMessage($message, $chat->customer()->first(), $chat->candidate()->first(), $resume,  $chat, $request->user()->role , $chat->job()->first()));
        }
       
        return response()->json([
            'status' => true
        ]);
    }

    public function getMessage($id):JsonResponse
    {
        $message =  Messages::where('chat_id', $id)->get();
        
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
