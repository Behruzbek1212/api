<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class RestoreController extends Controller
{
    /**
     * Restore passwords from the database.
     * 
     * @param  Request  $request
     * @return JsonResponse
     */
    public function restore(Request $request)
    {
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
    }
}
