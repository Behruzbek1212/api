<?php

namespace App\Http\Controllers;

use App\Models\SocialStatus;
use App\Http\Requests\StoreSocialStatusRequest;
use App\Http\Requests\UpdateSocialStatusRequest;

class SocialStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $socialStatus =  SocialStatus::all();


        return response()->json([
            'status' => true,
            'data' => $socialStatus
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocialStatusRequest $request)
    {
        $request->validated();
        
        $socialStatus  =  SocialStatus::query()->create([
            'name' => $request->name,
            'text' => $request->text
        ]);

        return response()->json([
            'status' => true,
            'data' =>   $socialStatus 
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialStatus $socialStatus)
    {
        return response()->json([
            'status' => true,
            'data ' =>  $socialStatus 
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialStatusRequest $request, SocialStatus $socialStatus)
    {
         $request->validated();
         $socialStatus->update([
            'name'=> $request->get('name'),
            'text'=> $request->get('text')
        ]);

        return response()->json([
            'status' => true,
            'data' => $socialStatus
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialStatus $socialStatus)
    {
        $socialStatus->delete();

        return response()->json([
            'status' => true,
            'data' => $socialStatus
        ]);
    }
}
