<?php

namespace App\Services;


use App\Http\Resources\StatisticResource;
use App\Repositories\StatisticRepositories;

use Carbon\Carbon;


class StatisticService
{
    
    public function getStatis($request, $name)
    {
        $start = $request->start  ?? null;
        $end = $request->end ?? null;
        $statisRepository = new StatisticRepositories();

        if ( $start == null && $end == null){
             
            $vacancies = $statisRepository->getChartDate($name ,Carbon::now()->subMonth(),  Carbon::now());
            

           return $vacancies;
        }

        $start_date = Carbon::create($start);
        $end_date = Carbon::create($end);
        $days_between = $start_date->diffInDays($end_date);
         
        if ($days_between > 60)
        {
            $vacancies = $statisRepository->getChartMonth($name ,$start,  $end);
            $statistic = StatisticResource::collection($vacancies);
            return  $statistic;
             
        }
        $vacancies = $statisRepository->getChartDate($name ,$start,  $end);


        return  $vacancies;
  
    }

    public function getHrCreateCalled( $request, $name)
    {
        $start = $request->start  ?? null;
        $end = $request->end ?? null;
        $statisRepository = new StatisticRepositories();

        if ( $start == null && $end == null){
             
            $called  = $statisRepository->getChartHrDate($name ,Carbon::now()->subMonth(),  Carbon::now());
            
            return  $called; 
        }

        $start_date = Carbon::create($start);
        $end_date = Carbon::create($end);
        $days_between = $start_date->diffInDays($end_date);
         
        if ($days_between > 60)
        {
            $called = $statisRepository->getChartHrMonth($name ,$start,  $end);
            $statistic = StatisticResource::collection($called);

            return $statistic; 
        }
        $called  = $statisRepository->getChartHrDate($name ,$start,  $end);


        return  $called; 
        
    }

}
