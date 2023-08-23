<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\AnnouncementServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
     public function all():JsonResponse
     {
        date_default_timezone_set('Asia/Tashkent');
        $currentDateTime = date('Y-m-d H:i:s');
      
        $announcementData = Announcement::where('deleted_at', null)
                        ->where('status', true)
                        ->where('time', '<=', $currentDateTime)->get();
    
        $updatedAnnouncements = $announcementData->map(function ($announcement) {
            return $announcement->update([
                'status' => false
            ]);
        });

        if(count($announcementData) !== 0){
            return response()->json([
               'status' => true,
               'data' => $announcementData
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => $announcementData
        ]);
    }


    public function create(Request $request):JsonResponse
    {
        try {
            $request->validate([
                'job_id' => 'required|integer'
            ]);
            
            $data =  AnnouncementServices::create($request);
            
            return response()->json([
                'status' => true,
                'message' => 'Announcement was created successfully',
                'data' => $data
            ]);
        }
        catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' =>  $e->getMessage(),
                'data' => []
            ]);
        }

    }


    public function storeConfirmation(Request $request) 
    {
        try {
            $request->validate([
                'announcement_id' => 'required|integer',
                'announcement_time' => 'required|date'
            ]);
    
            $data = AnnouncementServices::confirmation($request);
    
            return response()->json([
                'status' => true,
                'message' => 'Successfully confirmation'
            ]);
        } 
        catch (Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' =>  $e->getMessage(),
            ]);
        }
       
    }
}
