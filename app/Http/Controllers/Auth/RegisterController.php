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
            'password' => ['required', 'min:8'],
            'role' => ['required', 'in:admin,customer,candidate']
        ]);

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
     * Registration of new roles
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function role(Request $request): JsonResponse
    {
        match ($request->user()->role) {
            'candidate' =>
                $this->registerCandidate($request, $request->user()),

            'customer' =>
                $this->registerCustomer($request, $request->user()),

            default =>
                throw new Exception('Invalid role')
        };

        return response()->json([
            'status' => true,
            'message' => 'User successfully registered',
            'user' => User::query()
                ->with('customer', 'candidate')
                ->find($request->user()->id),
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
            'avatar' => ['nullable', 'string'],
            'owned_date' => ['required', 'date'],
            'location' => ['required', 'string'],
            'address' => ['required', 'string'],
            'about' => ['string', 'nullable']
        ]);

        $customer = $user->customer()->create([
            'name' => $request->input('name'),
            'about' => $request->input('about'),
            'avatar' => $request->input('avatar') ?? null,
            'owned_date' => $request->input('owned_date'),
            'location' => $request->input('location'),
            'address' => $request->input('address'),
            'active' => true
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
            'sex' => ['string', 'in:male,female', 'nullable'],
            'birthday' => ['required', 'date'],
            'languages' => ['nullable'],
            'spheres' => ['nullable'],
            'address' => ['required', 'string']
        ]);

        $candidate = $user->candidate()->create([
            'avatar' => $request->input('avatar') ?? null,
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'sex' => $request->input('sex') ?? 'male',
            'spheres' => $request->input('spheres'),
            'specialization' => $request->input('specialization'),
            'languages' => $request->input('languages'),
            'birthday' => $request->input('birthday'),
            'address' => $request->input('address'),
            'education_level' => $request->input('education_level') ?? null,
            'active' => true
        ]);

        return response()->json($candidate);
    }
}
