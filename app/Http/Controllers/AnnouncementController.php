<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Trafic;
use App\Models\TransactionHistory;
use App\Services\AnnouncementServices;
use Carbon\Carbon;
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


    public function dateCheck(Request $request):JsonResponse
    {
        $request->validate([
           'announ_date' => 'required|date_format:Y-m-d'
        ]);
        $carbon =  Carbon::now()->format('Y-m-d');
        try{
            if( $request->announ_date >=  $carbon ){

                $availableTimes = AnnouncementServices::checkDate($request, $carbon);
    
                return response()->json([
                    'status' => true,
                    'data' => $availableTimes
                ]);
            }

            return response()->json([
                'status' => false,
                'data' => []
            ]);

        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' =>  $e->getMessage(),
                'data' => []
            ]);
        }
        
    }


    public function create(Request $request):JsonResponse
    {
      
            $request->validate([
                'job_id' => 'required|integer',
                'bonus' => 'nullable|boolean'
            ]);
        try {
            $user = _auth()->user();
            $transactionHistory = TransactionHistory::query()->where('user_id', $user->id)->where('key', Trafic::KEY_FOR_TELEGRAM)->latest()->firstOrFail();
          
            if($transactionHistory !== null && $transactionHistory !== []){
             
                if($transactionHistory->service_count > 0){
                
                    $data =  AnnouncementServices::create($request, $transactionHistory);
            
                    return response()->json([
                        'status' => true,
                        'message' => 'Announcement was created successfully',
                        'data' => $data
                    ]);
                }
                return response()->json([
                    'status' =>  false,
                    'message' => 'You should  buy a new  service',
                ]);
            }
            return response()->json([
                'status' =>  false,
                'message' => 'You should buy a new service',
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
            $carbon =  Carbon::now()->format('Y-m-d H:i:s');
            
            if($request->announcement_time >= $carbon){
                $data = AnnouncementServices::confirmation($request);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully confirmation'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'error date'
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


    public function update(Request $request)
    {
        $request->validate([
            'announcement_id' => 'required|integer',
            'data' => 'required|array'
        ]);
        try {
            $announcement = AnnouncementServices::update($request);

            return response()->json([
                'status' => true,
                'message' => "Successfully updated announcement",
                'data' => $announcement
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' =>  $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
