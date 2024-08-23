<?php

namespace App\Http\Controllers\Bots\RailWay;

use App\Http\Controllers\Controller;
use App\Http\Requests\RailWay\StoreRailWayFileRequest;
use App\Http\Requests\RailWay\StoreRailWayHrRequest;
use App\Services\TemirSavdo\TemirSavdoHrService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RailWayHrController extends Controller
{
    public function createFile(StoreRailWayFileRequest $request):JsonResponse
    {
        $request->validated();

        try {
            $data =  TemirSavdoHrService::getInstance()->storeFile($request);
            return response()->json([
                'status' => true,
                'message' => 'Successfully',
                'file' => $data['file']
            ]);
        } catch(Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRailWayHrRequest $request):JsonResponse
    {

        $request->validated();


        try{
            $data = TemirSavdoHrService::getInstance()->store($request);

            return response()->json([
                'status' => true,
                'message' => "Successfully"
            ]);
        } catch(Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }


    }




    /**
     * Display the specified resource.
     */
    public function showData($token)
    {
        try{
            $data =  TemirSavdoHrService::getInstance()->showData($token) ;
            if(!$data){
                return response()->json([
                    'status' => false,
                    'error' => true,
                    'message' => 'not found',
                    'data' => [],
                    'message_id' => null
                ]);
            }
            return response()->json([
                'status' => true,
                'error' => false,
                'message' => 'Successfully',
                'data' => $data['data'],
                'message_id' => (int)$data['message_id']
            ]);
        }catch(Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }

    }
}
