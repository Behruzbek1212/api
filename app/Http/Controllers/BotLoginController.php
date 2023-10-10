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
        
        $bot = BotLogin::where('telegram_id', $request->telegram_id)->where('deleted_at', null)->latest()->firstOrFail();

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

    public function languageUpdate(Request $request):JsonResponse
    {
        $request->validate([
            'telegram_id' => 'required|integer',
            'token' => 'required|string',
            'language' => 'required|string|max:15',
        ]);

        $bot = BotLogin::where('telegram_id', $request->telegram_id)
                       ->where('token', $request->token) 
                       ->where('deleted_at', null)->latest()->firstOrFail();
        $bot->update([
           'language' => $request->language
        ]);            

        return response()->json([
            'status' => true,
            'message' => 'Successfully update',
        ]);
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
                       ->where('deleted_at', null)->first();
       
        if($bot !== null){
            $user = _auth()->user();

            /** @var HasAbilities|Builder $token */
            $token = $user->currentAccessToken();
            $token->delete();

            $bot->delete();

            return response()->json([
                'status' => true,
                'message' => 'Successfully deleted',
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => 'Not found',
        ]);
    }
}
