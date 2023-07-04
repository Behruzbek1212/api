<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TraficRequest;
use App\Http\Resources\TraficResource;
use App\Models\Trafic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TraficController extends Controller
{
    public function index(): JsonResponse
    {
        $tarfics = Trafic::query()
            // ->withTrashed()
            ->get();
        $list = TraficResource::collection($tarfics);
        return response()->json([
            'status' => true,
            'data' => $list
        ]);
    }

    public function create(TraficRequest $request): JsonResponse
    {

        if ($image = $request->file('image')) {
            $trafic_image = uploadFile($image, 'trafics');
        }

        Trafic::create([
            'image' => $trafic_image ?? null,
            'name' => $request->name ?? null,
            'price' => $request->price ?? null,
            'title' => $request->title ?? null,
            'description' => $request->description ?? null,
            'top_day' => $request->top_day ?? null,
            'count_rise' => $request->count_rise ?? null,
            'vip_day' => $request->vip_day ?? null,
            'type' => $request->type ?? null,
            'status' => $request->status ?? null,
        ]);

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $trafic = Trafic::query()
            // ->withTrashed()
            ->findOrFail($slug);
        return response()->json([
            'status' => true,
            'data' => $trafic
        ]);
    }

    public function edit(TraficRequest $request): JsonResponse
    {
        $trafic = Trafic::query()
            // ->withTrashed()
            ->findOrFail($request->get('slug'));

        if ($image = $request->file('image')) {
            $trafic_image = uploadFile($image, 'trafics');
        }
        $trafic->update([
            'image' => $trafic_image ?? null,
            'name' => $request->name ?? null,
            'price' => $request->price ?? null,
            'title' => $request->title ?? null,
            'description' => $request->description ?? null,
            'top_day' => $request->top_day ?? null,
            'count_rise' => $request->count_rise ?? null,
            'vip_day' => $request->vip_day ?? null,
            'type' => $request->type ?? null,
            'status' => $request->status ?? null,
        ]);

        if ($image = $request->file('image')) {
            $trafic_image = uploadFile($image, 'trafics');
        }

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function destroy(TraficRequest $request): JsonResponse
    {
        $trafic = Trafic::query()
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
