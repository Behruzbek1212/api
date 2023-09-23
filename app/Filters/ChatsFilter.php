<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ChatsFilter
{
    public static function apply($query)
    {
        // Apply common chat filters
        $orderBy = request('orderBy');
        $orderType = request('orderType');
        $min_year = request()->input('min_year') ?? null;
        $max_year = request()->input('max_year') ?? null;
        
        $query->when(request('sex'), function ($query) {
                    $query->whereHas('candidate', function ($query){
                        $query->where('sex', request('sex'));
                    });  
                })
                ->when(request('educ_level'), function($query) {
                    $query->whereHas('candidate', function ($query) {
                        $query->where('education_level', request('educ_level'));
                    }); 
                })
                ->when(request('min_age') || request('max_age'), function ($query){
                    $query->whereHas('candidate', function ($query) {
                        if(request('max_age') == null){
                            $query->whereRaw("YEAR(birthday) <= YEAR(NOW()) - ?", [request('min_age')]);
                        }elseif (request('min_age') == null){
                            $query->whereRaw("YEAR(birthday) >= YEAR(NOW()) - ?", [request('max_age')]);
                        } else {
                            $min_year = date('Y') - request('min_age');
                            $max_year = date('Y') - request('max_age');
                            $query->whereBetween(DB::raw('YEAR(birthday)'), [$max_year, $min_year]);
                        } 
                    }); 
                })
                ->when(request('start') || request('end') , function ($query){
                    $query->whereBetween('created_at', [request()->input('start'), request()->input('end')]);  
                })
                
                ->when(request('slug'), function ($query) {
                    $query->where('job_slug', request()->input('slug')); 
                })
                ->when(request('status'), function ($query) {
                        $query->where('status', request()->input('status'));

                })->when(request('languages'), function($query) {
                    $query->whereHas('candidate', function ($querys)  {
                         $languages =  json_decode(request()->input('languages'), true);
                            foreach ($languages as $language) {
                                $querys->whereJsonContains('languages', [
                                    ['language' => $language['language'], 'rate' => $language['rate']]
                                ]);
                            }
                    });
                })
                ->when(request('address'), function($query) {
                    $query->whereHas('candidate', function ($querys){
                            $querys->where('address', request('address'));
                    });
                });

      
        
      
        
        if ($orderBy && $orderType) {
            $query->whereHas('candidate', function ($query) use ($orderBy, $orderType) {
                $query->orderBy($orderBy, $orderType);
            });
        } else {
            $query->orderBy('updated_at', 'desc');
        }

      
        
        if($min_year !== null || $max_year !== null){
             
            if (strpos($min_year, '0.') !== false) {
                $min_years = intval(str_replace('0.', '', $min_year));
            } else {
                $min_years =  intval($min_year * 12)  ?? null;
            }
            if (strpos($max_year, '0.') !== false) {
                $max_years = intval(str_replace('0.', '', $max_year));
            } else {
                $max_years = intval($max_year * 12)  ?? null;
            }
            
        $datas =   $query->get()->filter(function ($chat) use ($min_years, $max_years, $min_year, $max_year) {
                $experience = optional($chat->resume)->experience;
                if($min_year == 0 && $max_years == 0){
                    return $experience  >= 0;
                } elseif($min_year == 0 && $max_years !== 0 ){
                    return $experience >= $min_years && $experience   <= $max_years;
                }  elseif($max_years == 0){
                    return $experience  >= $min_years;
                } elseif($min_years == 0){
                    return $experience  <= $max_years;
                }else {
                    return $experience >= $min_years && $experience   <= $max_years;
                }
            });
        } else {
            $datas = $query->get();
        }

        return self::paginateResults($datas, request('limit') ?? 15);
    }

    private static function paginateResults($query, $perPage)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
       

        $chatsPaginated = new LengthAwarePaginator(
            $query->forPage($page, $perPage),
            $query->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return $chatsPaginated;
    }
}
