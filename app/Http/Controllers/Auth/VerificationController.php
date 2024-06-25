<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordVerification;
use App\Services\EskizService;
use App\Services\MobileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Http;

class VerificationController extends Controller
{
    /**
     * Checking that the user's
     * phone number is not empty
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        $params = $request->validate([
            'phone' => ['unique:users,phone', 'regex:/[\d\w\+]+/i', 'required']
        ]);
        $ip = $request->ip();
        $token = Random::generate(5, '0-9');
        $phone = str_replace('+', '', $request->phone);

        PasswordVerification::query()->updateOrCreate(
            ['phone' => $params['phone']],
            ['token' => $token],
        );
        $message = "Foydalanuvchi IP manzili: $ip phone $phone";


        (new EskizService)
            ->send($phone, __('mobile.send.verification_code', ['code' => $token]));
        Http::withOptions(['verify' => false])->post('https://api.telegram.org/bot5777417067:AAGvh21OUGVQ7nmSnLbIhzTiZxoyMQMIZKk/sendMessage', [
            'chat_id' => '-4228941603',
            'text' => $message
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Phone number can be used'
        ]);
    }

    /**
     * Verify and delete verification
     * token in database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $params = $request->validate([
            'phone' => ['required', 'regex:/[\w\d\+]+/i', 'unique:users,phone'],
            'verification_code' => ['numeric', 'required']
        ]);

        $model = PasswordVerification::query()
            ->where('phone', '=', $params['phone'])
            ->where('token', '=', $params['verification_code']);

        if ( is_null($model->first()) ) return response()->json([
            'status' => false,
            'message' => __('error.verification_request_not_found')
        ]);

        $model->delete();
        return response()->json([
            'status' => true,
            'message' => 'Verification successful'
        ]);
    }
}
