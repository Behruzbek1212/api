<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LimitRequest;
use App\Http\Requests\TraficRequest;
use App\Http\Resources\LimitResource;
use App\Http\Resources\TraficResource;
use App\Models\LimitModel;
use App\Models\Trafic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LimitController extends Controller
{
    public function index(): JsonResponse
    {
        $tarfics = LimitModel::query()
            ->get();
        $list = LimitResource::collection($tarfics);
        return response()->json([
            'status' => true,
            'data' => $list
        ]);
    }

    public function create(LimitRequest $request): JsonResponse
    {

        LimitModel::create([
            'day' => $request->day ?? null,
            'price' => $request->price ?? null,
            'condidate_limit' => $request->condidate_limit ?? null,
            'code' => $request->code ?? null,
        ]);

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $limit = LimitModel::query()
            ->findOrFail($slug);
        return response()->json([
            'status' => true,
            'data' => $limit
        ]);
    }

    public function edit(LimitRequest $request): JsonResponse
    {
        $limit = LimitModel::query()
            ->findOrFail($request->get('slug'));
        $limit->update([
            'day' => $request->day ?? null,
            'price' => $request->price ?? null,
            'condidate_limit' => $request->condidate_limit ?? null,
            'code' => $request->code ?? null,
        ]);

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function destroy(LimitRequest $request): JsonResponse
    {

        $limit = LimitModel::query()
            ->withTrashed()
            ->findOrFail($request->slug);

        if (!$limit->trashed())
            $limit->delete();

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }
}
