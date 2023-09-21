<?php

namespace App\Http\Controllers;

use App\Models\BotLogin;
use App\Http\Requests\StoreBotLoginRequest;
use App\Http\Requests\UpdateBotLoginRequest;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;

class BotLoginController extends Controller
{
    

    /**
     * check user
     */
    public function check(Request $request):JsonResponse
    {
        $request->validate([
            'telegram_id' => 'required|integer'
        ]);
        
        $bot = BotLogin::where('telegram_id', $request->telegram_id)->where('deleted_at', null)->first();

        if($bot !== null){
            return response()->json([
                'status' => true,
                'data' => $bot
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBotLoginRequest $request):JsonResponse
    {
        $request->validated();
        try{
            BotLogin::query()->create([
                'telegram_id' => $request->telegram_id,
                'token' => $request->token,
                'language' => $request->language
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Successfully created',
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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request):JsonResponse
    {
        $request->validate([
            'telegram_id' => 'required|integer',
            'token' => 'required|string'
        ]);

        $bot = BotLogin::where('telegram_id', $request->telegram_id)
                       ->where('token', $request->token) 
                       ->where('deleted_at', null)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully deleted',
        ]);

    }
}
