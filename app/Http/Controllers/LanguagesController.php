<?php

namespace App\Http\Controllers;

use App\Models\Languages;
use App\Http\Requests\StoreLanguagesRequest;
use App\Http\Requests\UpdateLanguagesRequest;
use Illuminate\Http\JsonResponse;

class LanguagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $languages = Languages::all();

        return response()->json([
             'status'=> true,
             'data' => $languages
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguagesRequest $request)
    {
        $request->validated();
             
        $languages =  Languages::query()->create([
            'name' => $request->get('name'),
            'text' => $request->get('text')
        ]);

        return response()->json([
            'status'=> true,
            'data' => $languages
       ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Languages $languages):JsonResponse
    {
        return response()->json([
            'status'=> true,
            'data' => $languages
       ]);
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguagesRequest $request, Languages $languages)
    {
        $request->validated();

        $languages->update([
            'name'=> $request->get('name'),
            'text'=> $request->get('text')
        ]);

        return response()->json([
            'status' => true,
            'data' => $languages
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Languages $languages)
    {
        
        $languages->delete();

        return response()->json([
            'status' => true,
            'data' => $languages
        ]);
    }
}
