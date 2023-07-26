<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Services\AnnouncementServices;
use App\Services\JobServices;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        date_default_timezone_set('Asia/Tashkent');
        $currentDateTime = date('Y-m-d H:i:s');
        
        $announcementData = Announcement::where('deleted_at', null)
                        ->where('status', true)
                        ->where('time', '=' , $currentDateTime)->get();
    
        $updatedAnnouncements = $announcementData->map(function ($announcement) {
            return $announcement->update([
                'status' => false
            ]);
        });
       
    //     dd($announcementData);
        return response()->json([
            'status' => true,
            'data' => $announcementData
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnnouncementRequest $request)
    {
        $request->validated();
        
        $imageUrl =  JobServices::getInstance()->createJobBanner($request->data['company_name'], $request->data['title'], $request->data['salary'], $request->data['address'] );
        
        $data = $request->data;

        $data['image'] = $imageUrl;
    
        $announcement = Announcement::create([
            'post' => $data
        ]);
        
     
        return response()->json([
            'status' => true,
            'data' => $announcement,
            'message' => 'Successfully created'
        ]);
    }



    public function confirmation(Request $request) {
        
        $request->validate([
            'announcement_id' => 'required|integer'
        ]);

        $announcement = Announcement::find($request->announcement_id);

        $announcement->update([
           'status' => true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully confirmation'
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show($id):JsonResponse
    {
        $announcement = Announcement::find($id);
        return response()->json([
             'status' => true,
             'data' => $announcement == null ? [] : $announcement
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnnouncementRequest $request)
    {
        $request->validated();


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {   
      
         $request->validate([
             'id' => 'required|integer'
         ]);
           
         $announcement =  Announcement::query()->findOrFail($request->id);
         $announcement->delete();
        
        return response()->json([
             'status' => true,
             'data' => []
        ]);
    }
}
