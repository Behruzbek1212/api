<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    /**
     * Display all guides
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $authorized = auth('sanctum')->check();
        $limit = $request->get('limit') ?? -1;

        if ($authorized) {
            /** @var Authenticatable|User $user */
            $user = auth('sanctum')->user();

            $guides = Guide::query()
                ->where('blank', '=', '0')
                ->where('role', '=', $user->role)
                ->orWhere(function (Builder $query) {
                    $query->where('blank', '=', '0');
                    $query->where('role', '=', 'all');
                })
                ->limit($limit)
                ->orderByDesc('id')
                ->get();
        } else $guides = Guide::query()->limit($limit)->orderByDesc('id')->get();
        
        return response()->json([
            'status' => true,
            'guides' => $guides
        ]);
    }

    /**
     * Get guide information
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function get(string $slug): JsonResponse
    {
        $guide = Guide::query()->find($slug);

        if (is_null($guide))
            return response()->json([
                'status' => false,
                'message' => 'Guide not found'
            ]);

        return response()->json([
            'status' => true,
            'guide' => $guide
        ]);
    }

    // Other methods | TODO:Building 🏗
}
