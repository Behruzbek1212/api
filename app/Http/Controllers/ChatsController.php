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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
        $sex =  request()->input('sex') ?? null;
        $min_age = request()->input('min_age') ?? null;
        $max_age = request()->input('max_age') ?? null;
        $min_year = request()->input('min_year') ?? null;
        $max_year = request()->input('max_year') ?? null;
        $orderBy = request()->input('orderBy') ?? null;
        $orderType = request()->input('orderType') ?? null;

        $languages =  json_decode(request()->input('languages'), true) ?? null;


        $address = request()->input('address') ?? null;
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
                    ->with(['job','resume'])
                    ->where('deleted_at', null)

                    ->whereHas('job', function ($query) {
                        return $query->where('deleted_at', null);
                    })
                    ->when($sex, function ($query) use ($sex){
                        $query->whereHas('candidate', function ($query) use ($sex){
                            $query->where('sex', $sex);
                        });
                    })

                    ->when(request()->input('educ_level'), function($query) {
                        $query->whereHas('candidate', function ($query) {
                            $query->where('education_level', request()->input('educ_level'));
                        });
                    })
                    ->when($min_age || $max_age, function ($query) use ($min_age, $max_age){
                        $query->whereHas('candidate', function ($query) use ($min_age, $max_age){
                            if($max_age == null){
                                $query->whereRaw("YEAR(birthday) <= YEAR(NOW()) - ?", [$min_age]);
                            }elseif ($min_age == null){
                                $query->whereRaw("YEAR(birthday) >= YEAR(NOW()) - ?", [$max_age]);
                            } else {
                                $min_year = date('Y') - $min_age;
                                $max_year = date('Y') - $max_age;
                                $query->whereBetween(DB::raw('YEAR(birthday)'), [$max_year, $min_year]);
                            }
                        });
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
                    })->when($languages, function($query) use ($languages){
                        $query->whereHas('candidate', function ($querys) use ($languages) {
                                foreach ($languages as $language) {
                                    $querys->whereJsonContains('languages', [
                                        ['language' => $language['language'], 'rate' => $language['rate']]
                                    ]);
                                }

                        });
                    })
                    ->when($address, function($query) use ($address){
                        $query->whereHas('candidate', function ($querys) use ($address) {
                                $querys->where('address', $address);
                        });
                    })
                    ,

            default => null
        };


        if($user->role == 'customer'){

            $perPage = request()->get('limit') ?? 10;
            if($orderBy !== null && $orderType !== null){
                $chats->whereHas('candidate', function ($query) use ($orderBy,$orderType){
                    $query->orderBy($orderBy, $orderType);
                });
            } else {
                $chats->orderBy('updated_at', 'desc');
            }

            if($min_year !== null || $max_year !== null){

                if (strpos($min_year, '0.') !== false) {
                    $min_years = intval(str_replace('0.', '', $min_year));
                } else {
                    $min_years =  intval($min_year * 12)  ?? null;
                }
                if (strpos($max_year, '0.') !== false) {
                    $max_years = intval(str_replace('0.', '', $max_year));
                } else {
                    $max_years = intval($max_year * 12)  ?? null;
                }

                $datas =   $chats->get()->filter(function ($chat) use ($min_years, $max_years, $min_year, $max_year) {
                    $experience = optional($chat->resume)->experience;
                    if($min_year == 0 && $max_years == 0){
                        return $experience  >= 0;
                    } elseif($min_year == 0 && $max_years !== 0 ){
                        return $experience >= $min_years && $experience   <= $max_years;
                    }  elseif($max_years == 0){
                        return $experience  >= $min_years;
                    } elseif($min_years == 0){
                        return $experience  <= $max_years;
                    }else {
                        return $experience >= $min_years && $experience   <= $max_years;
                    }
                });
            } else {
                $datas = $chats->get();
            }

            $page = LengthAwarePaginator::resolveCurrentPage();
            $chatsPaginated = new LengthAwarePaginator(
                $datas->forPage($page, $perPage),
                $datas->count(),
                $perPage,
                $page,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );

            $data = ChatCandidateResource::collection($chatsPaginated);
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
