<?php

namespace App\Http\Controllers\Bots\RailWay;

use App\Http\Controllers\Controller;
use App\Http\Requests\RailWay\StoreRailWayRequest;
use App\Models\TemirSavdo\TemirSavdo;
use App\Services\TemirSavdo\TemirSavdoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RailWayController extends Controller
{
    public function index()
    {
        $data =  TemirSavdo::query()->get();

        return response()->json([
            'error' => false,
            'message' => 'Successfully',
            'users' => $data
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRailWayRequest $request)
    {
        $request->validated();

        try{
            $data = TemirSavdoService::getInstance()->store($request);

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
            $data = TemirSavdoService::getInstance()->showData($token) ;
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
       $count = TemirSavdo::query()->count();

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
