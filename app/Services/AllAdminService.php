<?php

namespace App\Services;

use App\Http\Resources\HrDoneWorkedResource;
use App\Models\Candidate;
use App\Models\Resume;
use App\Models\User;
use App\Traits\HasScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class AllAdminService
{
    use HasScopes;
    
     
    public static function getAllHr($start, $end, $limit) 
    {
        $hrs = User::where('deleted_at', null)->whereJsonContains('subrole', 'hr')->paginate($limit ?? 15);
        
        foreach($hrs as $hr){
            $list =  self::getStatistics($start, $end , $hr);      
            $data[] = $list;
        }

        return  [
            'data' => $data,
            'pagination' => [
                'total' => $hrs->total(),
                'per_page' => $hrs->perPage(),
                'current_page' => $hrs->currentPage(),
                'last_page' => $hrs->lastPage(),
            ],
        ];
    
    }


    public static function getOneHr($start, $end , $user_id, $limit,  $sortType)
    {
        
        $user = User::where('deleted_at', null)
                   ->where('id', $user_id)
                   ->whereJsonContains('subrole', 'hr')->first();
      
        $list =  self::getStatistics($start, $end , $user); 
        $history = Activity::whereJsonContains('properties->user_data->user_id', $user->id)
                ->whereIn('log_name', [
                    'candidates',
                    'comments',
                    'called_interviews'
                ])
                ->where('event', 'created')
                ->when($start && $end, function ($query) use ($start, $end){
                    $query->whereBetween('created_at', [$start, $end]);
                });
                     
        if($sortType !== null)
        { 
            $sortBy = 'created_at';
             
            $history->orderBy($sortBy, $sortType);
            
        } else {
            $history->orderByDesc('created_at');
        }
        

        $history = $history->paginate($limit ?? 15);

        $data = ['statis' => $list, 'history' => $history];
    
        return ['resource' => $data];
    }  

    
    public static function getStatistics($start, $end, $user) 
    {
        $activites = [
            'candidates',
            'comments',
            'called_interviews'
        ];
        
        $hrData = ['hr' => $user];

        foreach($activites as $activit){
            $count = Activity::whereJsonContains('properties->user_data->user_id', $user->id)
                    ->where('log_name', $activit)
                    ->where('event', 'created');
            if($start !== null && $end !== null){
                $count->whereBetween('created_at', [$start, $end]);
            }  
            if($activit == 'candidates'){
                $candidateIds = $count
                    ->pluck('subject_id')
                    ->toArray();
                  
                $candidate = Candidate::whereIn('id', $candidateIds)->pluck('user_id')->toArray();
               
                $resume = Resume::whereIn('user_id', $candidate)->pluck('user_id')->toArray();
                $uniqueId = array_unique($resume);
                $resumeCreatId = User::whereIn('id', $uniqueId)->count();
                $hrData['resume'] = $resumeCreatId;
            }

            $hrData[$activit] = $count->count(); 
        } 
       
        
      
       

        return $hrData;
    }
}
