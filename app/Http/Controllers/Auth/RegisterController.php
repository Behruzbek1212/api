<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use JsonException;

class RegisterController extends Controller
{
    protected Builder $model;

    /**
     * Controller constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = DB::table('password_verification');
    }

    /**
     * Registration of new users
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception | JsonException
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'verification_code' => ['required', 'numeric', 'min:5'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'in:admin,customer,candidate']
        ]);

//        $this->check_verification_code($request);

        /** @var User $user */
        $user = User::query()->create([
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
        ]);

        match ($user->role) {
            'candidate' =>
                $this->registerCandidate($request, $user),

            'customer' =>
                $this->registerCustomer($request, $user),

            default =>
                throw new Exception('Invalid role')
        };

        $user->markPhoneAsVerified();
        $token = $user->createToken($user->name . '-' . Hash::make($user->id))
            ->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User successfully registered',
            'user' => User::query()
                ->with('customer', 'candidate')
                ->find($user->id),
            'token' => $token
        ]);
    }

    /**
     * Check verification code
     *
     * @param Request $request
     * @return void
     *
     * @throws JsonException
     */
    protected function check_verification_code(Request $request): void
    {
        $phone = str_replace('+', '', $request->get('phone'));
        $model = $this->model->where('phone', $phone);

        if (is_null($model->first()))
            throw new JsonException('Verification code not found', 200);

        $token = $model->get('token')->first()->token;
        $code = $request->get('verification_code');

        if (! Hash::check($code, $token))
            throw new JsonException('Invalid verification code', 200);

        $model->delete();
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
            'avatar' => ['nullable', 'string'],
            'owned_date' => ['required', 'date'],
            'location' => ['required', 'string'],
            'address' => ['required', 'string']
        ]);

        $customer = $user->customer()->create([
            'name' => $request->input('name'),
            'avatar' => $request->input('avatar') ?? null,
            'owned_date' => $request->input('owned_date'),
            'location' => $request->input('location'),
            'address' => $request->input('address'),
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
            'name' => ['required', 'string'],
            'surname' => ['nullable', 'string'],
            'birthday' => ['required', 'date'],
            'spheres' => ['nullable'],
            'address' => ['required', 'string']
        ]);

        $candidate = $user->candidate()->create([
            'avatar' => $request->input('avatar') ?? null,
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'spheres' => $request->input('spheres'),
            'specialization' => $request->input('specialization'),
            'birthday' => $request->input('birthday'),
            'address' => $request->input('address'),
        ]);

        return response()->json($candidate);
    }
}
