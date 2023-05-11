<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TestUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = TestUser::all();

        return response()->json([
            'status' => true,
            'message' => 'worked'
        ]);
    }

    public function checkStatus(Request $request): JsonResponse
    {
        $params = $request->validate([
            'company_id' => ['numeric', 'required']
        ]);

        $customer = Customer::query()->findOrFail($params['company_id']);

        $result = $customer->services;

        if($result === null)
        {
            return response()->json([
                'status' => false,
                'result' => null
            ]);
        }

        return response()->json([
            'status' => true,
            'result' => $result
        ]);
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function register(Request $request): JsonResponse
    {
        $phone = $request['phone'];
        $exists = TestUser::where('phone', $phone)->exists();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => ('Этот номер телефона уже зарегистрирован.'),
            ], 422);
        }
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'surname' => ['required', 'string'],
            'sex' => ['required', 'string', 'in:male,female'],
            'position' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:test_users', 'min:8'],
            'company_id' => ['required', 'numeric'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $testUser = TestUser::create($validated);

        $token = $testUser->createToken($testUser->name . '-' . Hash::make($testUser->id))->plainTextToken;

        $customer = Customer::query()->findOrFail($validated['company_id']);

        $result = $customer->services;

        if($result === null)
        {
            return response()->json([
                'status' => false,
                'result' => null
            ]);
        }

        $statusTest = $result['testForCandidates'];
        if($statusTest['status'] === true)
        {
            $tempCount = $statusTest['count'];
            if($tempCount - 1 === 0){
                $result['testForCandidates']['count'] = 0; // Обновление значения поля "count"
                $result['testForCandidates']['status'] = false; // Обновление значения поля "status"
            } else {
                $result['testForCandidates']['count'] = $tempCount - 1; // Обновление значения поля "count"
                $result['testForCandidates']['status'] = true; // Обновление значения поля "status"
            }
            $customer->services = $result;
            $customer->save();
        }

        return response()->json([
            'status' => true,
            'user' => $testUser,
            'token' => $token,
        ], 201);
    }

    // Функция авторизации
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (Auth::guard('test_users')->attempt($validated)) {
            $user = auth('test_users')->user();
            $token = auth('test_users')->user()->createToken($user->name . '-' . Hash::make($user->id))->plainTextToken;

            return response()->json([
                'status' => true,
                'user' => auth('test_users')->user(),
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'error' => 'Unauthorized',
        ], 401);
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function addTestResult(Request $request):JsonResponse
    {
        $user = $request->user();
        $result = $request->get('result');

        // Make a copy of the test attribute
        $test = $user->test;

        // Initialize test attribute to empty array if it is null
        if ($test === null) {
            $test = [];
        }

        // Add new test result to the end of the test array
        $test[] = $result;

        // Set the modified test attribute back to the model
        $user->test = $test;

        // Save changes to database
        $user->save();

        return response()->json([
            'status' => true,
            'tests' => $user->test
        ]);
    }

    public function list(Request $request):JsonResponse
    {
        $token = $request->header('Authorization');
        if(!$token){
            return response()->json([
                'status'=> false,
                'message'=> 'Token is required!'
            ]);
        }

        $customer = $request->user()->customer;

        if(!$customer){
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        $list = TestUser::where('company_id', $customer->id)->get();

        return response()->json([
            'status' => true,
            'result' => $list
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param TestUser $testUser
     * @return Response
     */
    public function show(TestUser $testUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TestUser $testUser
     * @return Response
     */
    public function edit(TestUser $testUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TestUser $testUser
     * @return Response
     */
    public function update(Request $request, TestUser $testUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TestUser $testUser
     * @return Response
     */
    public function destroy(TestUser $testUser)
    {
        //
    }
}
