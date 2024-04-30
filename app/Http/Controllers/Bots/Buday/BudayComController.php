<?php

namespace App\Http\Controllers\Bots\Buday;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buday\StoreBudayComRequest;
use App\Models\Buday\BudayCom;
use App\Services\BudayCom\BudayComService;
use Exception;
use Illuminate\Http\JsonResponse;

class BudayComController extends Controller
{
    public function index()
    {
        $data =  BudayCom::query()->get();

        return response()->json([
            'error' => false,
            'message' => 'Successfully',
            'users' => $data
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBudayComRequest $request)
    {
        $request->validated();

        try{
            $data = BudayComService::getInstance()->store($request);

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
            $data = BudayComService::getInstance()->showData($token) ;
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
       $count = BudayCom::query()->count();

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
