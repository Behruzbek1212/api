<?php

namespace  App\Http\Controllers\Bots\MehriGiyo;

use App\Http\Controllers\Controller;
use App\Http\Requests\MehriGiyo\StoreMehriGiyoFileRequest;
use App\Http\Requests\MehriGiyo\StoreMehriGiyoHrRequest;
use App\Services\MehriGiyoService\MehriGiyoService;
use Exception;
use Illuminate\Http\JsonResponse;

class MehriGiyoHrController extends Controller
{
     /**
     * Show the form for creating a new resource.
     */
    public function createFile(StoreMehriGiyoFileRequest $request):JsonResponse
    {
        $request->validated();

        try {
            $data =  MehriGiyoService::getInstance()->storeFile($request);
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
    public function store(StoreMehriGiyoHrRequest $request):JsonResponse
    {  
    
        $request->validated();

    
        try{
            $data = MehriGiyoService::getInstance()->store($request);

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
            $data = MehriGiyoService::getInstance()->showData($token) ;
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
