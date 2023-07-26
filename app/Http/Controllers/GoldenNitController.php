<?php

namespace App\Http\Controllers;

use App\Models\GoldenNit;
use App\Http\Requests\StoreGoldenNitRequest;
use App\Http\Requests\UpdateGoldenNitRequest;

class GoldenNitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = GoldenNit::where('deleted_at', null)->get();
        
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoldenNitRequest $request)
    {
        $request->validated();

        $data = GoldenNit::query()->create([
             'name_surname' => $request->name_surname,
             'phone' => $request->phone,
             'seniority' => $request->seniority,
             'telegram_id' => $request->telegram_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User successfully created',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(GoldenNit $goldenNit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GoldenNit $goldenNit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoldenNitRequest $request, GoldenNit $goldenNit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoldenNit $goldenNit)
    {
        //
    }
}
