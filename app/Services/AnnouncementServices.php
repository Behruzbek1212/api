<?php

namespace App\Services;

use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Models\Location;
use App\Traits\HasScopes;
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
