<?php

namespace App\Repositories;



use Illuminate\Support\Facades\DB;

class StatisticRepositories
{
    public function getChartMonth( $dbtable ,$start,  $end)
    {
        if($dbtable == 'jobs'){
            $query  = DB::table($dbtable)
            ->selectRaw('year(created_at) year,   monthname(created_at) as date, count(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->where('deleted_at', null)
            ->groupBy('year',  'date')
            ->get();

            return $query;
        }
        $query  = DB::table($dbtable)->where('active', '1')
        ->selectRaw('year(created_at) year,   monthname(created_at) as date, count(*) as total')
        ->whereBetween('created_at', [$start, $end])
        ->where('deleted_at', null)
        ->groupBy('year',  'date')
        ->get();
        return $query;
    }
    
    public function getChartDate( $dbtable ,$start,  $end)
    {
        if($dbtable == 'jobs'){
            $query  = DB::table($dbtable)
            ->selectRaw('date(created_at) as date, count(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->where('deleted_at', null)
            ->groupBy('date')
            ->get();
            return $query;
        }
        $query  = DB::table($dbtable)->where('active', '1')
            ->selectRaw('date(created_at) as date, count(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->where('deleted_at', null)
            ->groupBy('date')
            ->get();
            
        return $query;

    }
  
}