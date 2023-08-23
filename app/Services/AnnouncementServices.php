<?php

namespace App\Services;

use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Models\Job;
use App\Traits\HasScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AnnouncementServices
{
    use HasScopes;
    
        
    public static function create($request) 
    {
        
        $user = _auth()->user();
        $vacansies = $user->customer->jobs()->where('id', $request->job_id)->first();
     
        $job =  new AnnouncementResource($vacansies);  
        $vac =  $job->toArray($vacansies);
        
        $data = $user->customer->announcement()->create([
            'post' => $vac
        ]);

        return $data;

    }


    public static function confirmation($request) 
    {
        $user = _auth()->user();
        $announcement = $user->customer->announcement()->where('id', $request->announcement_id)
                    ->update([
                        'status' => true,
                        'time' => $request->announcement_time
                    ]);

        return true;
    }


 

    
}
