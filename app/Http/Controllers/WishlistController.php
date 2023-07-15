<?php

namespace App\Http\Controllers;

use App\Http\Resources\WishlistCandidateResource;
use App\Http\Resources\WishlistCustomerResource;
use App\Models\Candidate;
use App\Models\Job;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $list = match ($request->user()->role) {
            'customer' => $request->user()->candidateWishlist()
                ->with('user')
                ->orderByDesc('id')
                ->paginate($request->limit ?? 15),

            'candidate' => $request->user()->jobsWishlist()
                ->with('customer')
                ->orderByDesc('id')
                ->paginate($request->limit ?? 15),
        };
        if($request->user()->role == 'candidate'){
            $data = WishlistCandidateResource::collection($list);
        } 
        if($request->user()->role == 'customer'){
            $data = WishlistCustomerResource::collection($list);
        }
        return  $this->successPaginate($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $params = $request->validate([
            'job_id' => ['string', 'nullable'],
            'candidate_id' => ['numeric', 'nullable']
        ]);

        match ($request->user()->role) {
            'candidate' => $request->user()->jobsWishlist()
                ->syncWithoutDetaching(Job::query()->findOrFail($params['job_id'])),

            'customer' => $request->user()->candidateWishlist()
                ->syncWithoutDetaching(Candidate::query()->findOrFail($params['candidate_id']))
        };

        return response()->json([
            'status' => true,
            'message' => 'Ok',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $params = $request->validate([
            'job_id' => ['string', 'nullable'],
            'candidate_id' => ['numeric', 'nullable']
        ]);

        match ($request->user()->role) {
            'candidate' => $request->user()->jobsWishlist()
                ->detach(Job::query()->findOrFail($params['job_id'])),

            'customer' => $request->user()->candidateWishlist()
                ->detach(Candidate::query()->findOrFail($params['candidate_id']))
        };

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }
}
