<?php

namespace  App\Http\Controllers\Bots\Yalpiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Yalpiz\StoreYalpizComCraterRequest;
use App\Http\Requests\Yalpiz\StoreYalpizFileRequest;
use App\Services\MehriGiyoService\YalpizComCraterService;
use Exception;
use Illuminate\Http\JsonResponse;

class YalpizComCraterController extends Controller
{
       /**
     * Show the form for creating a new resource.
     */
    public function createFile(StoreYalpizFileRequest $request):JsonResponse
    {
        $request->validated();

        try {
            $data =  YalpizComCraterService::getInstance()->storeFile($request);
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
    public function store(StoreYalpizComCraterRequest $request):JsonResponse
    {

        $request->validated();


        try{
            $data = YalpizComCraterService::getInstance()->store($request);

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
            $data =  YalpizComCraterService::getInstance()->showData($token) ;
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
