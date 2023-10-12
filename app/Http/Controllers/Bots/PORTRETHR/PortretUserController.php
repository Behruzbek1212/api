<?php

namespace  App\Http\Controllers\Bots\PORTRETHR;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortretUserRequest;
use App\Http\Requests\UpdatePortretUserRequest;
use App\Models\PortretHr\PortretUser;
use App\Services\PortretHr\PortretHrUserService;
use Exception;
use Illuminate\Http\JsonResponse;

class PortretUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $data = PortretUser::query()->get();

        return response()->json([
            'error' => false,
            'message' => 'Successfully',
            'users' => $data
        ]);
    }

 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortretUserRequest $request):JsonResponse
    {
        $request->validated();

        try{
            $data = PortretHrUserService::getInstance()->store($request);

            return response()->json([
                'status' => true,
                'error' => false,
                'message' => "Successfully"
            ]);
        } catch(Exception $e)
        {
            return response()->json([
                'status' => false,
                'error' =>  true,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showData($token):JsonResponse
    {
        try{
            $data = PortretHrUserService::getInstance()->showData($token) ;
            if(!$data){
                return response()->json([
                    'status' => false,
                    'error' => true,
                    'message' => 'not found',
                ]);
            }
            return response()->json([
                'status' => true,
                'error' => false,
                'message' => 'Successfully',
                'user_id' => $data['user_id'],
            ]);
        }catch(Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


     
    public function userCount():JsonResponse
    {
       $count =  PortretUser::query()->count();
       
       if (!$count) {
        return response()->json([
            'status' => false,
            'error' => true,
            'message' => 'Not found',
           
        ]);
        }
       return response()->json([
        'status' => true,
        'error' => false,
        'message' => 'Successfully',
        'count' => $count
    ]);

    }
   

   
}
