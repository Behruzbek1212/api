<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TraficRequest;
use App\Models\TraficPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TraficPriceController extends Controller
{
    public function index(): JsonResponse
    {
        $tarfics = TraficPrice::query()
            // ->withTrashed()
            ->get();
        // $list = TraficResource::collection($tarfics);
        return response()->json([
            'status' => true,
            'data' => $tarfics
        ]);
    }

    public function create(TraficRequest $request): JsonResponse
    {

        $request->validate([
            'count' => ['numeric', 'required'],
            'price' => ['numeric', 'required'],
            'discount' => ['numeric', 'required'],
        ]);
        TraficPrice::create([
            'count' => $request->count ?? null,
            'price' => $request->price ?? null,
            'discount' => $request->discount ?? null,
        ]);

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $trafic = TraficPrice::query()
            // ->withTrashed()
            ->findOrFail($slug);
        return response()->json([
            'status' => true,
            'data' => $trafic
        ]);
    }

    public function edit(TraficRequest $request): JsonResponse
    {
        $request->validate([
            'count' => ['numeric', 'required'],
            'price' => ['numeric', 'required'],
            'discount' => ['numeric', 'required'],
        ]);
        $trafic = TraficPrice::query()
            // ->withTrashed()
            ->findOrFail($request->get('slug'));

        $trafic->update([
            'count' => $request->count ?? null,
            'price' => $request->price ?? null,
            'discount' => $request->discount ?? null,
        ]);

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function destroy(TraficRequest $request): JsonResponse
    {
        $trafic = TraficPrice::query()
            // ->withTrashed()
            ->findOrFail($request->slug);

        // if (!$trafic->trashed())
        $trafic->delete();

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }
}
