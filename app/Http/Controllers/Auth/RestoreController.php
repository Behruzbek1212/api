<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Services\MobileService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nette\Utils\Random;
use Exception;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;

class RestoreController extends Controller
{
    /**
     * Restore passwords from the database.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function restore(Request $request): JsonResponse
    {
        $params = $request->validate([
            'phone' => ['regex:/[\d\w\+]+/i', 'nullable'],
            'email' => ['email', 'nullable'],
            'code' => ['numeric', 'required'],
            'password' => ['string', 'required']
        ]);

        $model = PasswordReset::query()
            ->where('token', '=', $params['code'])
            ->where(function (Builder $query) use ($params) {
                $query
                    ->where('phone', '=', $params['phone'])
                    ->orWhere('email', '=', $params['email']);
            });

        $user = User::query()
            ->where('phone', $params['phone'])
            ->orWhere('email', $params['phone']);

        if (is_null($model->first()) || is_null($user->first())) return response()->json([
            'status' => false,
            'message' => 'No user found with provided phone or email address or verification code is not valid'
        ]);

        $model->delete();
        $user->update(
            ['password' => Hash::make($params['password'])]
        );

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Send verification code
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function send(Request $request): JsonResponse
    {
        $params = $request->validate([
            'phone' => ['regex:/[\d\w\+]+/i', 'nullable'],
            'email' => ['email', 'nullable'],
            'type' =>  ['required', 'string', 'in:phone,email']
        ]);

        $code = Random::generate(5, '0-9');
        $user = User::query()
            ->where('phone', '=', $params['phone'])
            ->orWhere('email', '=', $params['email'])
            ->first( [ '*' ] );

        if ( is_null($user) ) return response()->json([
            'status' => false,
            'message' => 'User not found'
        ]);

        match ($params['type']) {
            'phone' =>
                $this->withPhone($params['phone'], $code),

            'email' =>
                $this->withEmail($params['email'], $code),

            default =>
                throw new Exception('Invalid request type')
        };

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Verify sent verification code
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $params = $request->validate([
            'phone' => ['regex:/[\d\w\+]+/i', 'required'],
            'email' => ['email', 'required'],
            'code' => ['numeric', 'required']
        ]);

        $model = PasswordReset::query()
            ->where('phone', '=', $params['phone'])
            ->orWhere('email', '=', $params['email'])
            ->first(['token']);

        if ( is_null($model) ) return response()->json([
            'status' => false,
            'message' => 'No user found with provided phone or email address'
        ]);

        if ($params['code'] != $model['token']) return response()->json([
            'status' => false,
            'message' => 'Verification code is not valid',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Restore password with phone number.
     *
     * @param integer|string $phone
     * @param integer|string $code
     * @return void
     *
     * @throws Exception
     */
    protected function withPhone(int|string $phone, int|string $code): void
    {
        if ( !$phone || !$code ) {
            throw new Exception('Invalid phone number');
        }

        PasswordReset::query()->updateOrCreate(
            ['phone' => $phone],
            ['token' => $code]
        );

        (new MobileService)
            ->send($phone, __('mobile.send.verification_code', ['code' => $code]));
    }

    /**
     * Restore password with email address.
     *
     * @param string $email
     * @param integer|string $code
     * @return void
     *
     * @throws Exception
     */
    protected function withEmail(string $email, int|string $code): void
    {
        if ( !$email || !$code ) {
            throw new Exception('Invalid email address');
        }

        PasswordReset::query()->updateOrCreate(
            ['email' => $email],
            ['token' => $code]
        );

        (new MailMessage)
            ->subject(__('mobile.send.verification_code', ['code' => $code]))
            ->line(__('mobile.send.verification_code', ['code' => $code]));
    }
}
