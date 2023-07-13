<?php

namespace App\Http\Controllers\Admin;

use App\Models\ResumeBall;
use App\Http\Requests\StoreResumeBallRequest;
use App\Http\Requests\UpdateResumeBallRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ResumeBallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $data = ResumeBall::all();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResumeBallRequest $request):JsonResponse
    {
        $request->validated();

        $data = ResumeBall::query()->create([
            'name' => $request->name,
            'ball' => $request->ball
        ]);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ResumeBall $resumeBall):JsonResponse
    {

        return response()->json([
            'status' => true,
            'data' => $resumeBall
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResumeBallRequest $request)
    {
        $request->validated();
        $resumeBall = ResumeBall::findOrFail($request->resumeBall_id);
        $resumeBall->update([
             'name' => $request->name,
             'ball' => $request->ball
        ]);

        return response()->json([
            'status' => true,
            'data' => $resumeBall
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $resumeBall = ResumeBall::query()->find($id);
        $resumeBall->delete();
        return response()->json([
            'status' => true,
            'data' => $resumeBall
        ]);
    }
}
