<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
     public function all( ):JsonResponse
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
       
        return response()->json([
            'status' => true,
            'data' => $announcementData
        ]);
     }
}
