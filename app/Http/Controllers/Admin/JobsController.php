<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Job;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index(): JsonResponse
    {
        $jobs = Job::query()
            ->withTrashed()
            ->with(['customer' => function (BelongsTo $query) {
                $query->where('active', '=', true);
            }])
            ->whereNot('status', '=', 'closed')
            ->orderByDesc('updated_at');

        return response()->json([
            'status' => true,
            'data' => $jobs->paginate(20)
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $this->validateParams($request, [
            'customer_id' => ['numeric', 'required']
        ]);

        $customer = Customer::query()
            ->findOrFail($request->get('customer_id'));

        $customer->jobs()->create(array_merge(
            $request->except( ['customer_id', 'slug'] ),
            ['slug' => null, 'status' => 'approved']
        ));

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $job = Job::query()
            ->withTrashed()
            ->with(['customer' => function (BelongsTo $query) {
                $query->where('active', '=', true);
            }])
            ->whereNOt('status', '=', 'closed')
            ->findOrFail($slug);

        return response()->json([
            'status' => true,
            'data' => $job
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        $this->validateParams($request, [
            'slug' => ['string', 'required']
        ]);

        $job = Job::query()
            ->withTrashed()
            ->findOrFail($request->get('slug'));

        $job->update( $request->except( ['slug'] ) );

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $params = $request->validate([
            'slug' => ['string', 'required']
        ]);

        $job = Job::query()
            ->withTrashed()
            ->findOrFail($params['slug']);

        if (! $job->trashed())
            $job->delete();

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
            'location_id' => ['numeric', 'required'],
            'category_id' => ['numeric', 'required'],
            'education_level' => ['string', 'nullable'],
            'experience' => ['string', 'required'],
            'about' => ['string', 'required'],
            'work_type' => ['string', 'in:fulltime,remote,partial,hybrid', 'required'],
            'sphere' => ['array', 'required'],
            'languages' => ['array', 'nullable'],
            'salary' => ['array:amount,currency,agreement', 'required'],
        ], $rule));
    }
}
