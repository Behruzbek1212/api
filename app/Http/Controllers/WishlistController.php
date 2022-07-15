<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
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
        /** @var Authenticatable|User $user */
        $user = $request->user('sanctum');

        return response()->json([
            'status' => true,
            'list' => $user->wishlist
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
        /** @var Authenticatable|User $user */
        $user = $request->user('sanctum');
        $job = Job::query()->find($request->input('job_id'));

        if (is_null($job)) {
            return response()->json([
                'status' => false,
                'message' => 'Job not found'
            ]);
        }

        $user->wishlist()->attach($job);

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
        /** @var Authenticatable|User $user */
        $user = $request->user('sanctum');
        $job = Job::query()->find($request->input('job_id'));

        if (is_null($job)) {
            return response()->json([
                'status' => false,
                'message' => 'Job not found'
            ]);
        }

        $user->wishlist()->detach($job);

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }
}
