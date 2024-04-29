<?php

namespace App\Http\Controllers\Bots\Yalpiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Yalpiz\StoreYalpizComRequest;
use App\Models\Yalpiz\YalpizCom as YalpizYalpizCom;
use App\Models\YalpizCom;

use App\Services\MehriGiyoService\YalpizComService;
use Exception;
use Illuminate\Http\JsonResponse;

class YalpizComController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data =  YalpizYalpizCom::query()->get();

        return response()->json([
            'error' => false,
            'message' => 'Successfully',
            'users' => $data
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreYalpizComRequest $request)
    {
        $request->validated();

        try{
            $data = YalpizComService::getInstance()->store($request);

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
            $data = YalpizComService::getInstance()->showData($token) ;
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
       $count =  YalpizYalpizCom::query()->count();

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
