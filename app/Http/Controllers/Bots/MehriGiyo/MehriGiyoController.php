<?php

namespace   App\Http\Controllers\Bots\MehriGiyo;

use App\Http\Controllers\Controller;
use App\Http\Requests\MehriGiyo\StoreMehriGiyoRequest;
use App\Models\MehriGiyo\MehriGiyo ;
use App\Services\MehriGiyoService\MehriGiyoUserService;
use Exception;
use Illuminate\Http\JsonResponse;

class MehriGiyoController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $data =  MehriGiyo::query()->get();

        return response()->json([
            'error' => false,
            'message' => 'Successfully',
            'users' => $data
        ]);
    }

 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMehriGiyoRequest $request):JsonResponse
    {
        $request->validated();

        try{
            $data = MehriGiyoUserService::getInstance()->store($request);

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
            $data = MehriGiyoUserService::getInstance()->showData($token) ;
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
                'user_id' => (int)$data['user_id'],
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
       $count =  MehriGiyo::query()->count();
       
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
