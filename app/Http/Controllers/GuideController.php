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
    public function index()
    {
        //
    }

    /**
     * Display all guides
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $authorized = auth('sanctum')->check();

        if ($authorized) {
            /** @var Authenticatable|User */
            $user = auth('sanctum')->user();

            $guides = Guide::query()
                ->where('blank', '=', '0')
                ->where('role', '=', $user->role)
                ->orWhere(function (Builder $query) {
                    $query->where('blank', '=', '0');
                    $query->where('role', '=', 'all');
                })
                ->get();
        } else $guides = Guide::all();

        return response()->json([
            'status' => true,
            'guides' => $guides
        ]);
    }

    /**
     * Get guide information
     *
     * @param integer|string $id
     * @return JsonResponse
     */
    public function get(int|string $id): JsonResponse
    {
        $guide = Guide::query()->find($id);

        return response()->json([
            'status' => true,
            'guide' => $guide
        ]);
    }

    // Other methods | TODO:Building 🏗
}
