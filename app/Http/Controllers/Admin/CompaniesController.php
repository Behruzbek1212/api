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
            $customers->where('name', 'like', '%'.$request->get('title').'%');

        return response()->json([
            'status' => true,
            'data' => $customers->paginate(20)
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $this->validateParams($request, []);
        $password = Random::generate();

        try {
            $user = User::query()->create(array_merge(
                $request->only([ 'phone', 'email' ]),
                ['password' => Hash::make($password)],
                ['role' => 'customer']
            ));

            $user->customer()->create(array_merge(
                $request->only([ 'name', 'about', 'owned_date', 'location', 'address' ]),
                ['avatar' => $request->get('avatar') ?? null]
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
            'Sizning JOBO.uz ga kirish parolingiz: ' . $password
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

        $customer = Customer::query()
            ->withTrashed()
            ->findOrFail($request->get('id'));

        $customer->update(array_merge(
            $request->only([ 'name', 'about', 'owned_date', 'location', 'address' ]),
            ['avatar' => $request->get('avatar') ?? null]
        ));

        $customer->user()->update(array_merge(
            $request->only([ 'phone', 'email' ]),
        ));

        return response()->json([
            'status' => true,
            'message' => []
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

        if (! $customer->trashed())
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
            'email' => ['email', 'unique:users,email', 'nullable']
        ], $rule));
    }
}
