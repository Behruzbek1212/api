<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Services\MobileService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class CompaniesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $customers = Customer::query()
            ->withTrashed()
            ->with(['user' => function (BelongsTo $query) {
                $query->where('role', '=', 'customer');
            }])
            ->where('active', '=', true)
            ->withCount('jobs')
            ->orderByDesc('updated_at');

        if ($request->has('title'))
            $customers->where('name', 'like', '%' . $request->get('title') . '%');

        return response()->json([
            'status' => true,
            'data' => $customers->paginate(20)
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        // dd($request->all());
        $this->validateParams($request, []);
        $password = Random::generate();

        try {
            $user = User::query()->create(array_merge(
                $request->only(['phone', 'email']),
                ['password' => Hash::make($password)],
                ['role' => 'customer']
            ));
            $user->customer()->create(array_merge(
                $request->only(['name', 'about', 'owned_date', 'location', 'address']),
                ['avatar' => $request->get('avatar') ?? null],
                ['active' => true],
            ));
        } catch (QueryException $exception) {
            return response()->json([
                'status' => false,
                'error' => true,
                'message' => $exception->getMessage()
            ]);
        }

        (new MobileService())->send(
            $request->get('phone'),
            "Sizning JOBO.uz ga kirish parolingiz: " . $password .
                "\nQuyidagi link orqali tezkor kirishni amalga oshirishingiz mumkin: " .
                vsprintf("https://jobo.uz/auth/verifier/%s/?p=%s", [$password, $request->get('phone')])
        );

        return response()->json([
            'status' => true,
            'password' => $password,
            'data' => []
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $customer = Customer::query()
            ->withTrashed()
            ->with(['user' => function (BelongsTo $query) {
                $query->where('role', '=', 'customer');
            }])
            ->where('active', '=', true)
            ->withCount('jobs')
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $customer
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        $this->validateParams($request, [
            'id' => ['integer', 'required'],
            'phone' => ['numeric', 'required'],
            'email' => ['email', 'required']
        ]);
        // dd($request->all());
        // dd(_auth()->user()->phone == $request->phone);
        // $ds = User::where('email', $request->email)->get();
        // dd($ds);
        $user = _auth()->user();

        if ($user->email !== $request->email) {
            $request->validate([
                'email' => ['email', 'unique:users,email']
            ]);
        }

        $customer = Customer::query()
            ->withTrashed()
            ->findOrFail($request->get('id'));
        if (!is_null($request->input('email')) && $request->input('email') != $user->email) {
            $customer->update(array_merge(
                $request->only(['name', 'about', 'owned_date', 'location', 'address']),
                ['avatar' => $request->get('avatar') ?? null]
            ));

            $customer->user()->update(array_merge(
                $request->only(['phone', 'email']),
            ));
        }
        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function addServices(Request $request): JsonResponse
    {
        $request->validate([
            'id' => ['integer', 'required'],
            'service' => ['array', 'required']
        ]);
        //        $this->validateParams($request, [
        //            'id' => ['integer', 'required'],
        ////            'service' => ['json', 'required']
        //        ]);

        $customer = Customer::query()
            ->withTrashed()
            ->findOrFail($request->get('id'));

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found'
            ]);
        }


        $services = $customer->services ?? (object)[];

        $newService = (object)$request->get('service');
        $services = (object)array_merge((array)$services, (array)$newService);

        $requestData = $request->all();
        $requestData['services'] = $services;

        $customer->update($requestData);

        return response()->json([
            'status' => true,
            'result' => $customer->services
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $params = $request->validate([
            'id' => ['integer', 'required']
        ]);

        $customer = Customer::query()
            ->withTrashed()
            ->findOrFail($params['id']);

        if (!$customer->trashed())
            $customer->delete();

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    /**
     * Validate request params
     *
     * @param Request $request
     * @param array $rule
     * @return void
     */
    private function validateParams(Request $request, array $rule): void
    {
        $request->validate(array_merge([
            'name' => ['string', 'required'],
            'about' => ['string', 'required'],
            'avatar' => ['string', 'nullable'],
            'owned_date' => ['date', 'required'],
            'location' => ['numeric', 'required'],
            'address' => ['string', 'required'],
            'phone' => ['numeric', 'unique:users,phone', 'required'],
            'email' => ['email', 'unique:users,email', 'nullable'],
            // 'limit_id' => ['numeric', 'required'],
            // 'limit_start_day' => ['date', 'required'],
            // 'limit_end_day' => ['date', 'required']
        ], $rule));
    }
}
