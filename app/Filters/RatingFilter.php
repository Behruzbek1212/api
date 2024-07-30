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

        $query->with(['candidate.user.resumes'])
            ->whereHas('candidate', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->when($title, function ($query) use ($title) {
                $query->whereHas('candidate', function ($query) use ($title) {
                    $query->where(function ($query) use ($title) {
                        $query->where('name', 'like', '%' . $title . '%')
                              ->orWhere('surname', 'like', '%' . $title . '%')
                              ->orWhere('specialization', 'like', '%' . $title . '%');
                    });
                });
            })
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
            ->when(request('min_age') || request('max_age'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    if (request('max_age') === null) {
                        $query->whereRaw("YEAR(birthday) <= YEAR(CURDATE()) - ?", [request('min_age')]);
                    } elseif (request('min_age') === null) {
                        $query->whereRaw("YEAR(birthday) >= YEAR(CURDATE()) - ?", [request('max_age')]);
                    } else {
                        $min_year = date('Y') - request('min_age');
                        $max_year = date('Y') - request('max_age');
                        $query->whereBetween(DB::raw('YEAR(birthday)'), [$max_year, $min_year]);
                    }
                });
            })
            ->when(request('languages'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $languages = json_decode(request()->input('languages'), true);
                    foreach ($languages as $language) {
                        $query->whereJsonContains('languages', [
                            ['language' => $language['language'], 'rate' => $language['rate']]
                        ]);
                    }
                });
            })
            ->when(request('address'), function ($query) {
                $query->whereHas('candidate', function ($query) {
                    $query->where('address', request('address'));
                });
            });

        if ($min_year !== null || $max_year !== null) {
            $datas = $query->get()->filter(function ($chat) use ($min_year, $max_year) {
                $experience = optional($chat['candidate']['user']['resumes']->first())->experience;

                $min_years = $min_year !== null ? (strpos($min_year, '0.') !== false ? intval(str_replace('0.', '', $min_year)) : intval($min_year * 12)) : null;
                $max_years = $max_year !== null ? (strpos($max_year, '0.') !== false ? intval(str_replace('0.', '', $max_year)) : intval($max_year * 12)) : null;

                if ($min_years === null && $max_years === null) {
                    return true;
                } elseif ($min_years === null) {
                    return $experience <= $max_years;
                } elseif ($max_years === null) {
                    return $experience >= $min_years;
                } else {
                    return $experience >= $min_years && $experience <= $max_years;
                }
            });
        } else {
            $datas = $query->get();
        }

        $filteredResults = collect($datas)->filter(function ($item) use ($test) {
            $sortArr = [];
            $efficiensy = 0;
            foreach ($item['result'] as $value) {
                if ($test === null) {
                    $sortArr[] = $value;
                } elseif ($value['quizGroup'] === $test) {
                    $efficiensy = $value['efficiensy'];
                    $sortArr[] = $value;
                }
            }
            $max = $test === null ? self::getAveragePercentage($item['result']) : $efficiensy;
            $item['result'] = [];
            $item['percentage'] = intval($max);
            $item['result'] = $sortArr;
            return $item;
        });

        $sortedResults = $filteredResults->sortByDesc(function ($item) {
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
