<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $list = match ($request->user()->role) {
            'customer' => $request->user()->candidateWishlist()
                ->with('user'),

            'candidate' => $request->user()->jobsWishlist()
                ->with('customer'),
        };

        return response()->json([
            'status' => true,
            'list' => $list->orderByDesc('id')
                ->paginate(15)
        ]);
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
