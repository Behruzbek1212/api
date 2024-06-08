<?php

namespace App\Http\Controllers\Bots\Azaly;

use App\Http\Controllers\Controller;
use App\Http\Requests\Azaly\StoreAzalyFileRequest;
use App\Http\Requests\Azaly\StoreAzalyHrRequest as AzalyStoreAzalyHrRequest;

use App\Services\Azaly\AzalyHrService;
use Exception;
use Illuminate\Http\JsonResponse;

class AzalyHrController extends Controller
{
    public function createFile(StoreAzalyFileRequest $request):JsonResponse
    {
        $request->validated();

        try {
            $data =  AzalyHrService::getInstance()->storeFile($request);
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
    public function store(AzalyStoreAzalyHrRequest $request):JsonResponse
    {

        $request->validated();


        try{
            $data = AzalyHrService::getInstance()->store($request);

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
            $data =  AzalyHrService::getInstance()->showData($token) ;
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
