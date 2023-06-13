<?php

namespace App\Http\Controllers;

use App\Models\LanguageLevels;
use App\Http\Requests\StoreLanguageLevelsRequest;
use App\Http\Requests\UpdateLanguageLevelsRequest;
use Illuminate\Http\JsonResponse;

class LanguageLevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $languageLevels = LanguageLevels::all();

        return response()->json([
            'status'=> true,
            'data' =>  $languageLevels
      ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguageLevelsRequest $request):JsonResponse
    {
        $request->validated();

        $languageLevels = LanguageLevels::query()->create([
            'name'=> $request->get('name'),
            'text'=> $request->get('text')
        ]);

        return response()->json([
              'status'=> true,
              'data' =>  $languageLevels
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LanguageLevels $languageLevels):JsonResponse
    {
        return response()->json([
            'status'=> true,
            'data' =>  $languageLevels
      ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageLevelsRequest $request, LanguageLevels $languageLevels)
    {
        $request->validated();

        $languageLevels->update([
            'name'=> $request->get('name'),
            'text'=> $request->get('text')
        ]);

        return response()->json([
              'status'=> true,
              'data' =>  $languageLevels
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LanguageLevels $languageLevels)
    {
        $languageLevels->delete();

        return response()->json([
            'status'=> true,
            'data' =>  $languageLevels
      ]);
    }
}
