<?php

namespace App\Filters;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RatingFilter
{
    public static function apply($query)
    {
        $test = request('quiz_gruop') ?? null;
        $take = request('take') ?? 100;
        $title = request('title') ?? null;
        $min_year = request()->input('min_year') ?? null;
        $max_year = request()->input('max_year') ?? null;

        $query->whereHas('candidate', function ($query) {
                $query->where('deleted_at', null);
            })
            ->when($title, function ($query) use ($title) {
                $query->whereHas('candidate', function ($querys) use ($title) {
                    $querys->where('name', 'like', '%' . $title . '%');
                    $querys->orWhere('surname', 'like', '%' . $title . '%');
                    $querys->orWhere('specialization', 'like', '%' . $title . '%');
                });
            })
            ->where('deleted_at', null)
            ->when(request('sex'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $query->where('sex', request('sex'));
                });
            })
            ->when(request('spheres'), function ($query)  {
                $query->whereHas('candidate', function ($querys) {
                    $querys->whereJsonContains('spheres', request('spheres'));
                });

            })
            ->when(request('educ_level'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $query->where('education_level', request('educ_level'));
                });
            })
            ->when(request('min_age') || request('max_age'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    if (request('max_age') == null) {
                        $query->whereRaw("YEAR(birthday) <= YEAR(NOW()) - ?", [request('min_age')]);
                    } elseif (request('min_age') == null) {
                        $query->whereRaw("YEAR(birthday) >= YEAR(NOW()) - ?", [request('max_age')]);
                    } else {
                        $min_year = date('Y') - request('min_age');
                        $max_year = date('Y') - request('max_age');
                        $query->whereBetween(DB::raw('YEAR(birthday)'), [$max_year, $min_year]);
                    }
                });
            })
            ->when(request('languages'), function ($query) {
                $query->whereHas('candidate', function ($querys) {
                    $languages =  json_decode(request()->input('languages'), true);
                    foreach ($languages as $language) {
                        $querys->whereJsonContains('languages', [
                            ['language' => $language['language'], 'rate' => $language['rate']]
                        ]);
                    }
                });
            })
            ->when(request('address'), function ($query) {
                $query->whereHas('candidate', function ($querys) {
                    $querys->where('address', request('address'));
                });
            });

        if ($min_year !== null || $max_year !== null) {

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
                $experience =   optional($chat['candidate']['user']['resumes']->first())->experience;

                if ($min_year == 0 && $max_years == 0) {
                    return $experience  >= 0;
                } elseif ($min_year == 0 && $max_years !== 0) {
                    return $experience >= $min_years && $experience   <= $max_years;
                } elseif ($max_years == 0) {
                    return $experience  >= $min_years;
                } elseif ($min_years == 0) {
                    return $experience  <= $max_years;
                } else {
                    return $experience >= $min_years && $experience   <= $max_years;
                }
            });
        } else {
            $datas = $query->get();
        }
        $filteredResults = collect($datas)->filter(function ($item) use ($test) {
            $sortArr = [];
            $efficiensy = 0;
            foreach ($item['result'] as $key => $value) {
                if ($test == null) {
                    $sortArr[] = $value;
                } else {
                    if ($value['quizGroup'] == $test) {
                        $efficiensy  = $value['efficiensy'];
                        $sortArr[] = $value;
                    }
                }
            }
            $max = $test == null ? self::getAveragePercentage($item['result']) :  $efficiensy;
            $item['result'] = [];
            $item['percentage'] = intval($max);
            $item['result'] = $sortArr;
            return $item;
        });

        $sortedResults = $filteredResults
            ->sortByDesc(function ($item) {
                return [
                    $item['percentage'],
                    $item['candidate']['user']['resumes']->isNotEmpty(),
                    optional($item['candidate']['user']['resumes']->first())->percentage ?? 0
                ];
            })
            ->take($take)
            ->values();

        return self::paginateResults($sortedResults, request('limit') ?? 20);
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
    private static function getAveragePercentage($tests)
    {
        $exam = Exam::count();
        if ($tests) {
            $totalEfficiency = array_reduce($tests, function ($acc, $test) {
                return $acc + $test['efficiensy'];
            }, 0);
            $averagePercentage = round($totalEfficiency / $exam);
            return $averagePercentage;
        }
    }
}
