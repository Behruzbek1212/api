<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Registration of new users
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'in:admin,customer,candidate']
        ]);

        /** @var User */
        $user = User::query()->create([
            'phone' => $request->get('phone'),
            'password' => Hash::make($request->get('password')),
            'role' => $request->get('role'),
        ]);

        switch ($user->role) {
            case 'candidate':
                $this->registerCandidate($request, $user);
                break;
            case 'customer':
                $this->registerCustomer($request, $user);
                break;
            case 'admin':
            default:
                throw new Exception('Invalid role');
        }

        $token = $user->createToken($user->name . '-' . Hash::make($user->id))
            ->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Register a customer user
     *
     * @param  Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function registerCustomer(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string'],
            'owned_date' => ['required', 'date'],
            'address' => ['required', 'string']
        ]);

        $customer = Customer::query()->create([
            'user_id' => $user->id,
            'name' => $request->get('name'),
            'owned_date' => $request->get('owned_date'),
            'address' => $request->get('address'),
        ]);

        return response()->json($customer);
    }

    /**
     * Register a candidate account
     *
     * @param  Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function registerCandidate(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['nullable', 'string'],
            'birthday' => ['required', 'date'],
            'address' => ['required', 'string']
        ]);

        $candidate = Candidate::query()->create([
            'user_id' => $user->id,
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'birthday' => $request->get('birthday'),
            'address' => $request->get('address'),
        ]);

        return response()->json($candidate);
    }
}
