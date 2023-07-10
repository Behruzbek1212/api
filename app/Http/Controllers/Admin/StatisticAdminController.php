<?php

namespace App\Http\Controllers\Admin;

use App\Models\Candidate;
use App\Models\Chat\Chat;
use App\Models\Customer;
use App\Models\Job;
use App\Models\Resume;
use App\Repositories\StatisticRepositories;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticResource;

class StatisticAdminController extends Controller
{
    public function getStatis ():JsonResponse
    {
        $currentDate =  Carbon::now();
    
        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $currentDate->toDateTimeString());
        $carbon->subMonth();

        $resumes = Resume::where('deleted_at', null)->get();
        $visits = $resumes->sum('visits');
        $download = $resumes->sum('downloads');
        $chats = Chat::where('deleted_at', null)->count();

        $candidates = Candidate::where('active', '1')->where('deleted_at', null)->count();
        $customers = Customer::where('active', '1')->where('deleted_at', null)->count();
        $vacancies = Job::where('deleted_at', null)->count();
        
        $candidateLastMount = Candidate::where('active', '1')
                     ->whereBetween('created_at',  [ $carbon->toDateTimeString(), $currentDate->toDateTimeString()])
                     ->where('deleted_at', null)
                     ->count();
        $customerLastMount =  Customer::where('active', '1')
                     ->whereBetween('created_at',  [ $carbon->toDateTimeString(), $currentDate->toDateTimeString()])
                     ->where('deleted_at', null)
                     ->count();   
       
        $allData = [
            'allCandidat' => $candidates,
            'allCustomer' => $customers,
            'allVacancies' => $vacancies,
            'resume' => $resumes->count(),
            'chats' => $chats,
            'visit' => $visits,
            'download' => $download
        ];
        $lastMonthData = [
            'candidate' => $candidateLastMount,
            'customer' => $customerLastMount,
        ];
        return response()->json([
            'status' => true,
            'allData' => $allData,
            'lastMonthData' => $lastMonthData,
        ]);
    }

     /**
     * Customer data for chart
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function getCustomer(Request $request):JsonResponse
    {     
        $start = $request->start  ?? null;
        $end = $request->end ?? null;
        $statisRepository = new StatisticRepositories();
         
        if ( $start == null && $end == null){
             
            $customers = $statisRepository->getChartDate('customers' ,Carbon::now()->subMonth(),  Carbon::now());
            

         return response()->json([
            'status'=> true,
            'data' => $customers
         ]);
        }

        $start_date = Carbon::create($start);
        $end_date = Carbon::create($end);
        $days_between = $start_date->diffInDays($end_date);
         
        if ($days_between > 60)
        {
           
            $customers = $statisRepository->getChartMonth('customers' ,$start,  $end);
            
            $statistic = StatisticResource::collection($customers);

             return response()->json([
                'status'=> true,
                'data' => $statistic
             ]);
             
        }
     
        $customers = $statisRepository->getChartDate('customers' ,$start,  $end);


        return response()->json([
           'status'=> true,
           'data' => $customers
        ]);


    } 


    
    /**
     * Candidates data for chart
     * 
     * @param  Request $request
     * @return JsonResponse
     */

    public function getCandidates(Request $request):JsonResponse
    {
        $start = $request->start  ?? null;
        $end = $request->end ?? null;
        $statisRepository = new StatisticRepositories();

        if ( $start == null && $end == null){
             
            $candidates = $statisRepository->getChartDate('candidates' ,Carbon::now()->subMonth(),  Carbon::now());
            

         return response()->json([
            'status'=> true,
            'data' => $candidates
         ]);
        }

        $start_date = Carbon::create($start);
        $end_date = Carbon::create($end);
        $days_between = $start_date->diffInDays($end_date);
         
        if ($days_between > 60)
        {
    
            $candidates = $statisRepository->getChartMonth('candidates' ,$start,  $end);
            $statistic = StatisticResource::collection($candidates);
    
             return response()->json([
                'status'=> true,
                'data' => $statistic
             ]);
             
        }
        $candidates = $statisRepository->getChartDate('candidates' ,$start,  $end);

         return response()->json([
            'status'=> true,
            'data' => $candidates
         ]);
    }


    /**
     * Vacancies data for chart
     * 
     * @param  Request $request
     * @return JsonResponse
     */


    public function getVacancies(Request $request):JsonResponse
    {
        $start = $request->start  ?? null;
        $end = $request->end ?? null;
        $statisRepository = new StatisticRepositories();

        if ( $start == null && $end == null){
             
            $vacancies = $statisRepository->getChartDate('jobs' ,Carbon::now()->subMonth(),  Carbon::now());
            

         return response()->json([
            'status'=> true,
            'data' => $vacancies
         ]);
        }

        $start_date = Carbon::create($start);
        $end_date = Carbon::create($end);
        $days_between = $start_date->diffInDays($end_date);
         
        if ($days_between > 60)
        {
            $vacancies = $statisRepository->getChartMonth('jobs' ,$start,  $end);
            $statistic = StatisticResource::collection($vacancies);
             return response()->json([
                'status'=> true,
                'data' => $statistic
             ]);
             
        }
        $vacancies = $statisRepository->getChartDate('jobs' ,$start,  $end);


        return response()->json([
           'status'=> true,
           'data' => $vacancies
        ]);
        
    }
}