<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Http\Requests\StoreEducationLevelRequest;
use App\Http\Requests\UpdateEducationLevelRequest;
use Illuminate\Http\JsonResponse;

class EducationLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $educationLevels =  EducationLevel::all();


        return response()->json([
            'status' => true,
            'data' => $educationLevels
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEducationLevelRequest $request)
    {
        
        $request->validated();
        
        $educationLevels = EducationLevel::query()->create([
            'name' => $request->name,
            'text' => $request->text
        ]);

        return response()->json([
            'status' => true,
            'data' => $educationLevels
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(EducationLevel $educationLevel)
    {
        return response()->json([
            'status' => true,
            'data' => $educationLevel
        ]);
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEducationLevelRequest $request, EducationLevel $educationLevel)
    {
        $request->validated();

        $educationLevel->update([
            'name'=> $request->get('name'),
            'text'=> $request->get('text')
        ]);

        return response()->json([
            'status' => true,
            'data' => $educationLevel
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationLevel $educationLevel)
    {
        $educationLevel->delete();
         
        return response()->json([
            'status' => true,
            'data' => $educationLevel
        ]);
    }
}
