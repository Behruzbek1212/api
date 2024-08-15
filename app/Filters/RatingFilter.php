<?php

namespace App\Filters;

use App\Models\Exam;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RatingFilter
{
    public static function apply($query)
    {
        $test = request('quiz_gruop') ?? null;
        $take = request('take') ?? 100;
        $title = request('title') ?? null;
        $min_year = request('min_year') ?? null;
        $max_year = request('max_year') ?? null;

        $query->with(['candidate' => function ($query) use ($title) {
                if ($title) {
                    $query->where(function ($q) use ($title) {
                        $q->where('name', 'like', '%' . $title . '%')
                          ->orWhere('surname', 'like', '%' . $title . '%')
                          ->orWhere('specialization', 'like', '%' . $title . '%');
                    });
                }
                $query->whereNull('deleted_at');
            }])
            ->when(request('sex'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $query->where('sex', request('sex'));
                });
            })
            ->when(request('spheres'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $query->whereJsonContains('spheres', request('spheres'));
                });
            })
            ->when(request('educ_level'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $query->where('education_level', request('educ_level'));
                });
            })
            ->when($min_year || $max_year, function ($query) use ($min_year, $max_year) {
                $query->whereHas('candidate', function ($query) use ($min_year, $max_year) {
                    if ($min_year) {
                        $min_birth_year = date('Y') - $min_year;
                        $query->whereRaw("YEAR(birthday) <= ?", [$min_birth_year]);
                    }
                    if ($max_year) {
                        $max_birth_year = date('Y') - $max_year;
                        $query->whereRaw("YEAR(birthday) >= ?", [$max_birth_year]);
                    }
                });
            })
            ->when(request('languages'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $languages = json_decode(request('languages'), true);
                    foreach ($languages as $language) {
                        $query->whereJsonContains('languages', [
                            'language' => $language['language'],
                            'rate' => $language['rate']
                        ]);
                    }
                });
            })
            ->when(request('address'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $query->where('address', request('address'));
                });
            });

        // Optimized Experience Filtering
        $datas = $query->get()->filter(function ($chat) use ($min_year, $max_year) {
            $experience = optional($chat['candidate']['user']['resumes']->first())->experience ?? 0;
            return ($min_year === null || $experience >= $min_year) && ($max_year === null || $experience <= $max_year);
        });

        // Filter and sort results
        $filteredResults = $datas->filter(function ($item) use ($test) {
            $efficiency = 0;
            if ($test) {
                $item['result'] = array_filter($item['result'], function ($result) use ($test, &$efficiency) {
                    if ($result['quizGroup'] == $test) {
                        $efficiency = $result['efficiensy'];
                        return true;
                    }
                    return false;
                });
                $item['percentage'] = $efficiency;
            } else {
                $item['percentage'] = self::getAveragePercentage($item['result']);
            }
            return $item;
        });

        $sortedResults = $filteredResults->sortByDesc(function ($item) {
            return [
                $item['percentage'],
                optional($item['candidate']['user']['resumes']->first())->percentage ?? 0
            ];
        })->take($take)->values();

        return self::paginateResults($sortedResults, request('limit') ?? 20);
    }

    private static function paginateResults($query, $perPage)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $total = $query->count();
        $results = $query->slice(($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
    }

    private static function getAveragePercentage($tests)
    {
        $examCount = Exam::count();
        $totalEfficiency = collect($tests)->sum('efficiensy');
        return $examCount > 0 ? round($totalEfficiency / $examCount) : 0;
    }
}

