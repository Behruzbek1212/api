<?php

namespace App\Http\Controllers\Bots\Buday;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buday\StoreBudayComHrRequest;
use App\Http\Requests\Buday\StoreBudayFileRequest;

use App\Http\Requests\UpdateBudayComHrRequest;
use App\Models\BudayComHr;
use App\Services\MehriGiyoService\BudayComHrService;
use Exception;
use Illuminate\Http\JsonResponse;

class BudayComHrController extends Controller
{
    public function createFile(StoreBudayFileRequest $request):JsonResponse
    {
        $request->validated();

        try {
            $data =  BudayComHrService::getInstance()->storeFile($request);
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
    public function store(StoreBudayComHrRequest $request):JsonResponse
    {

        $request->validated();


        try{
            $data = BudayComHrService::getInstance()->store($request);

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
            $data =  BudayComHrService::getInstance()->showData($token) ;
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
