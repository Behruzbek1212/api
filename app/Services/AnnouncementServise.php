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
    
    public static function getInctance(): AnnouncementServices
    {
        return new static();
    }
    
    // public function create($request) {
     
    //     $job =  AnnouncementResource::collection(Job::find($request->job_id));  
        
    //     $data = Announcement::query()->create([
    //         'post' => $job
    //     ]);

    //     return true;

    // }

    
}
