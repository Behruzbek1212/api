<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MobileService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class VerificationController extends Controller
{
    protected Builder $model;

    /**
     * Controller constructor
     *
     * @return VerificationController
     */
    public function __construct()
    {
        $this->model = DB::table('password_verification');

        return $this;
    }

    /**
     * Checking that the user's
     * phone number is not empty
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'numeric', 'unique:users,phone']
        ]);

        $token = Random::generate(5, '0-9');
        $phone = str_replace('+', '', $request->input('phone'));
        $model = $this->model->where('phone', $phone);

        if (! is_null($model->first()))
            $model->update([
                'token' => Hash::make($token)
            ]);
        else
            $this->model->insert([
                'phone' => $phone,
                'token' => Hash::make($token)
            ]);

        (new MobileService)
            ->send($phone, __('mobile.send.verification_code', ['code' => $token]));

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
        $request->validate([
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'verification_code' => ['required', 'numeric']
        ]);

        $phone = str_replace('+', '', $request->input('phone'));
        $model = $this->model->where('phone', $phone);

        if (is_null($model->first()))
            return response()->json([
                'status' => false,
                'message' => __('error.verification_request_not_found')
            ]);

        $token = $model->get('token')->first()->token;
        $verification_code = $request->input('verification_code');
        if (! Hash::check($verification_code, $token))
            return response()->json([
                'status' => false,
                'message' => __('error.verification_code_is_invalid')
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Verification successful'
        ]);
    }
}
