<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        /** @var Authenticatable|User */
        $user = $request->user('sanctum');

        return response()->json([
            'status' => true,
            'list' => $user->resumes
        ]);
    }

    /**
     * Display a resume.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        /** @var Authenticatable|User */
        $user = $request->user('sanctum');

        return response()->json([
            'status' => true,
            'list' => $user->resumes
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
        /** @var Authenticatable|User */
        $user = $request->user('sanctum');

        return response()->json([
            'status' => true,
            'message' => 'Ok',
            'data' => $user
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
        /** @var Authenticatable|User */
        $user = $request->user('sanctum');

        return response()->json([
            'status' => true,
            'message' => 'Ok',
            'data' => $user
        ]);
    }
}
