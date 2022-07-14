<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MobileService;
use Illuminate\Http\Request;
use Nette\Utils\Random;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RestoreController extends Controller
{
    protected Builder $model;

    /**
     * Restore controller constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->model = DB::table('password_resets');
    }

    /**
     * Restore passwords from the database.
     * 
     * @param  Request  $request
     * @return JsonResponse
     */
    public function restore(Request $request)
    {
        $model = $this->model
            ->where('phone', '=', $request->input('phone'))
            ->orWhere('email', '=', $request->input('email'));

        $user = User::query()
            ->where('phone', $request->input('phone'))
            ->orWhere('email', $request->input('phone'));

        if (is_null($model->first()) || is_null($user->first())) {
            return response()->json([
                'status' => false,
                'message' => 'No user found with provided phone or email address'
            ]);
        }

        if (!Hash::check($request->input('code'), $model->first()->token)) {
            return response()->json([
                'status' => false,
                'message' => 'Verification code is not valid'
            ]);
        }

        $model->delete();
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Send verification code
     * 
     * @param  Request  $request
     * @return JsonResponse
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone' => ['numeric', 'nullable'],
            'email' => ['email', 'nullable'],
            'type' => ['required', 'string', 'in:phone,email']
        ]);

        $user = User::query()
            ->where('phone', '=', $request->input('phone'))
            ->orWhere('email', '=', $request->input('email'))
            ->first();

        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        $code = Random::generate(5, '0-9');

        switch ($request->input('type')) {
            case 'phone':
                $this->withPhone($request->input('phone'), $code);
                break;
            case 'email':
                $this->withEmail($request->input('email'), $code);
                break;
            default:
                throw new Exception('Invalid request type');
        }

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Verify sended verification code
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request)
    {
        $model = $this->model
            ->where('phone', '=', $request->input('phone'))
            ->orWhere('email', '=', $request->input('email'))
            ->first();

        if (is_null($model)) {
            return response()->json([
                'status' => false,
                'message' => 'No user found with provided phone or email address'
            ]);
        }

        if (!Hash::check($request->input('code'), $model->token)) {
            return response()->json([
                'status' => false,
                'message' => 'Verification code is not valid',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Restore password with phone number.
     * 
     * @param string|integer $phone
     * @param string|integer $code
     * @return void|Exception
     */
    protected function withPhone($phone, $code)
    {
        if (!$phone || !$code) {
            throw new Exception('Invalid phone number');
        }

        $this->model->insert([
            'phone' => $phone,
            'token' => Hash::make($code)
        ]);

        $message = "Jobo.uz | Код подтверждение: " . $code;
        (new MobileService)
            ->send($phone, $message);
    }

    /**
     * Restore password with phone number.
     * 
     * @param string $email
     * @param string|integer $code
     * @return void|Exception
     */
    protected function withEmail($email, $code)
    {
        if (!$email || !$code) {
            throw new Exception('Invalid email address');
        }

        $this->model->insert([
            'email' => $email,
            'token' => Hash::make($code)
        ]);

        (new MailMessage)
            ->subject("Код подтверждение")
            ->line("Код подтверждение: " . $code);
    }
}
