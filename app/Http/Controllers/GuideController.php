<?php

namespace App\Http\Controllers;

use App\Models\Guide;
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
     * Show all guides
     * 
     * @return JsonResponse
     */
    public function all()
    {
        $authorized = auth('sanctum')->check();

        if ($authorized) {
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
     * @param string|integer $id
     * @return JsonResponse
     */
    public function get($id)
    {
        $guide = Guide::find($id);

        return response()->json([
            'status' => true,
            'guide' => $guide
        ]);
    }

    // Other methods | TODO:Building 🏗
}
