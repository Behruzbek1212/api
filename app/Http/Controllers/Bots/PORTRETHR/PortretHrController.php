<?php

namespace  App\Http\Controllers\Bots\PORTRETHR;

use App\Http\Controllers\Controller;

use App\Http\Requests\StorePortretHrRequest;
use App\Http\Requests\UpdatePortretHrRequest;
use Illuminate\Http\Request;
use App\Models\PortretHr\PortretHr;
use App\Services\PortretHr\PortretHrService;
use Exception;
use Illuminate\Http\JsonResponse;

class PortretHrController extends Controller
{
    
    /**
     * Show the form for creating a new resource.
     */
    public function createFile(Request $request):JsonResponse
    {
        $request->validate([
            'token' => 'string|required',
            'file' => 'required|mimes:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt|max:5000000',
        ]);

        try {
            $data =  PortretHrService::getInstance()->storeFile($request);
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
    public function store(StorePortretHrRequest $request):JsonResponse
    {
        $request->validated();
        try{
            $data = PortretHrService::getInstance()->store($request);

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
            $data = PortretHrService::getInstance()->showData($token) ;
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PortretHr $portretHr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortretHrRequest $request, PortretHr $portretHr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PortretHr $portretHr)
    {
        //
    }
}
