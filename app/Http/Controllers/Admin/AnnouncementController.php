<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use Illuminate\Http\Request;
use App\Models\Job;
use File;

use App\Services\AnnouncementServices;
use App\Services\JobServices;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $announcementData = Announcement::where('deleted_at', null)
                            ->orderByDesc('updated_at');

        return response()->json([
            'status' => true,
            'data' => $announcementData->paginate($request->limit ?? null)
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnnouncementRequest $request)
    {
        $request->validated();
        
        $imageUrl =  JobServices::getInstance()->createJobBanner($request->data['company_name'], $request->data['title'], $request->data['salary'], $request->data['address'] , $request->data['post_number'] , $request->data['bonus'] ?? false);
        
        $data = $request->data;

        $data['image'] = $imageUrl;
        $data['bonus'] = $request->data['bonus'] ?? false;
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
            'announcement_id' => 'required|integer',
            'announcement_time' => 'required|date'
        ]);
        $carbon =  Carbon::now()->format('Y-m-d H:i:s');
        if($request->announcement_time >= $carbon){
            $announcement = Announcement::find($request->announcement_id);

            $announcement->update([
               'status' => true,
               'time' => $request->announcement_time
            ]);

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
        $data = Announcement::find($request->announcement_id);
        $oldImage =  $data->post['image'];
        $filePath = parse_url($oldImage, PHP_URL_PATH);
        
        $filePath = ltrim($filePath, '/');
       
        if (File::exists(public_path($filePath))) {
            File::delete(public_path($filePath));
        }
        $bonus = $request->post['bonus'] ?? false;
        
        $imageUrl =  JobServices::getInstance()->createJobBanner($request->post['company_name'], $request->post['title'], $request->post['salary'], $request->post['address'] , $request->post['post_number'], $bonus);
        
        $post = $request->post;

        $post['image'] = $imageUrl;
       
        $updateData = $data->update([
            'post' => $post
        ]);
        $announcement = Announcement::find($request->announcement_id);
        return response()->json([
              'status' => true,
              'data' => $announcement
        ]);
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
             'message' => 'Successfully deleted announcement'
        ]);
    }
}
