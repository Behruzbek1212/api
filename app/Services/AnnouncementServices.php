<?php

namespace App\Services;

use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Models\Location;
use App\Traits\HasScopes;
use Carbon\Carbon;
use File;

class AnnouncementServices
{
    use HasScopes;
    
        
    public static function create($request, $transaction) 
    {
        
        $user = _auth()->user();
        
        $vacansies = $user->customer->jobs()->where('id', $request->job_id)->first();
        
        $job =  new AnnouncementResource($vacansies);  
        $vac =  $job->toArray($vacansies);
        
        $data = $user->customer->announcement()->create([
            'post' => $vac
        ]);
        $transactionCount = $transaction->service_count - 1;
        
        $transaction->service_count = $transactionCount;
        $transaction->save();
        return $data;

    }
    
    public static function checkDate($request, $carbon)
    {
        $announcement = Announcement::where('deleted_at', null)->whereDate('time', $request->announ_date)->pluck('time')->map(function ($time) {
            return Carbon::parse($time)->format('H:i');
        })->toArray();

        $date = Carbon::parse($request->announ_date);

        $times = [];
        for ($i = 0; $i < 24; $i++) {
            for ($j = 0; $j < 60; $j += 30) {
                $time = $date->setTime($i, $j);
                $times[] = $time->format('H:i');
            }
        }

        $availableTimes = array_diff($times, $announcement);

        if ($request->announ_date ==  $carbon) {
            $currentHour = Carbon::now('Asia/Tashkent')->format('H:i');
            
            $availableTimes = array_filter($availableTimes, function ($time) use ($currentHour) {
                return $time >= $currentHour;
            });
        }

        return array_values($availableTimes);
    }

    public static function confirmation($request) 
    {
        $user = _auth()->user();
        $announcement = $user->customer->announcement()->find($request->announcement_id);
        $announcement->update([
                    'status' => true,
                    'time' => $request->announcement_time
                ]);
        
        return true;
    }



    public static function update($request)
    {
        $user = _auth()->user();
        $data = $user->customer->announcement()->findOrFail($request->announcement_id);
        $oldImage =  $data->post['image'];
       
        $filePath = parse_url($oldImage, PHP_URL_PATH);
        
        $filePath = ltrim($filePath, '/');
       
        if (File::exists(public_path($filePath))) {
            File::delete(public_path($filePath));
        }
        
        
        $imageUrl =  JobServices::getInstance()->createJobBanner($request->data['company_name'], $request->data['title'], $request->data['salary'], $request->data['address'] , $request->data['post_number']);
        
        $post = $request->data;
        
        $post['image'] = $imageUrl;
        $post['hash_tag'] = self::getHashTab($request->data['title'], $request->data['address']);
     
        $updateData = $data->update([
            'post' => $post
        ]);
        
        $announcement = Announcement::find($request->announcement_id);
        return $announcement;
    }


 
    public static function getHashTab($title, $address)
    {
        if($title !== null && $address !==null){
            $textlovercase = strtolower($title);
            $textpreg = '#'. preg_replace('/\s+/', '', $textlovercase); 
            $location = Location::find($address)['name']['uz'];
            $patterns = array('t.', 'sh.', 'vil.');

            foreach($patterns as $pattern) {
                $location = str_replace($pattern,'', $location);
            }
            $textLoc = '#'. preg_replace('/\s+/', '', strtolower(trim($location))) ;
            
            
            return [$textpreg . ' ' .$textLoc];
        }

        return [];
    }
    
}
