<?php

namespace App\Filters;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
class CandidateFilter extends QueryFilter
{
    public function name($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where(DB::raw("concat(name,' ',surname)"), 'like', '%' . $data . '%');
        });
    }


    public function title($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereHas('user.resumes', function (Builder $query) use ($data) {
                $query->whereRaw(
                    'lower(json_unquote(json_extract(`data`, \'$."position"\'))) like ?',
                    ['%' . strtolower($data) . '%']
                );
            });
        });
    }

    public function sphere($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereJsonContains('spheres', $data);
        });
    }

    public static function  applys($query)
    {
        $min_year = request('min_year') ?? null;
        $max_year = request('max_year') ?? null;
        
        $query->when(request('name'), function ($query) {
                   $query->where(DB::raw("concat(name,' ',surname)"), 'like', '%' . request('name') . '%');
                })
                ->when(request('sex'), function ($query) {
                    $query->where('sex', request('sex'));
                })
                ->when(request('title'), function ($query){
                    $query->whereHas('user.resumes', function (Builder $query) {
                        $query->whereRaw(
                            'lower(json_unquote(json_extract(`data`, \'$."position"\'))) like ?',
                            ['%' . strtolower(request('title')) . '%']
                        );
                    });
                })
                ->when(request('work_type'), function ($query) {
                    $query->whereHas('user.resumes', function ($query) {
                        $query->whereJsonContains("data->work_type" , request('work_type'));
                    });
                })
                ->when(request('min_age') || request('max_age'), function ($query)
                {
                    if (request('max_age') == null) {
                       $query->whereRaw("YEAR(birthday) <= YEAR(NOW()) - ?", [request('min_age')]);
                    } elseif (request('min_age') == null) {
                       $query->whereRaw("YEAR(birthday) >= YEAR(NOW()) - ?", [request('max_age')]);
                    } else {
                       $min_year = date('Y') - request('min_age');
                       $max_year = date('Y') - request('max_age');
                       $query->whereBetween(DB::raw('YEAR(birthday)'), [$max_year, $min_year]);
                    }   
                })
                ->when(request('languages'), function($query) 
                {
                    $languages =  json_decode(request()->input('languages'), true);
                    foreach ($languages as $language) {
                        $query->whereJsonContains('languages', [
                            ['language' => $language['language'], 'rate' => $language['rate']]
                        ]);
                    }
                })
                ->when(request('sphere'), function ($query) {
                    $query->whereJsonContains('spheres', request('sphere'));
                })
                ->when(request('address'), function($query) {
                    $query->where('address', request('address'));
                });

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
      
        $datas =  $query->get()->filter(function ($query) use ($min_years, $max_years, $min_year, $max_year) {
             
                $experience = optional($query['user']['resumes']->first())->experience;
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
            })->values();
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
