<?php

namespace App\Filters;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RatingFilter
{
    public static function apply(Builder $query): LengthAwarePaginator
    {
        $query = self::applyFilters($query);
        $results = self::processResults($query);
        return self::paginateResults($results);
    }

    private static function applyFilters(Builder $query): Builder
    {
        return $query->with(['candidate' => function ($query) {
            $query->whereNull('deleted_at');
            self::filterByTitle($query);
        }])
        ->when(request('sex'), function ($query) {
            return self::filterBySex($query);
        })
        ->when(request('spheres'), function ($query) {
            return self::filterBySpheres($query);
        })
        ->when(request('min_age') || request('max_age'), function ($query) {
            return self::filterAge($query);
        })
        ->when(request('educ_level'), function ($query) {
            return self::filterByEducationLevel($query);
        })
        ->when(request('min_year') || request('max_year'), function ($query) {
            return self::filterByAge($query);
        })
        ->when(request('languages'), function ($query) {
            return self::filterByLanguages($query);
        })
        ->when(request('address'), function ($query) {
            return self::filterByAddress($query);
        });
    }

    private static function filterByTitle($query)
    {
        $title = request('title');
        if ($title) {
            $query->where(function ($q) use ($title) {
                $q->where('name', 'like', '%' . $title . '%')
                  ->orWhere('surname', 'like', '%' . $title . '%')
                  ->orWhere('specialization', 'like', '%' . $title . '%');
            });
        }
        return $query;
    }

    private static function filterBySex($query)
    {
        $query->whereHas('candidate', function ($q) {
            $q->where('sex', request('sex'));
        });
        return $query;
    }
    private static function filterAge($query)
    {
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
        return $query;
    }

    private static function filterBySpheres($query)
    {
        $query->whereHas('candidate', function ($q) {
            $q->whereJsonContains('spheres', request('spheres'));
        });
        return $query;
    }

    private static function filterByEducationLevel($query)
    {
        $query->whereHas('candidate', function ($q) {
            $q->where('education_level', request('educ_level'));
        });
        return $query;
    }

    private static function filterByAge($query)
    {
        $query->whereHas('candidate', function ($q) {
            $minYear = request('min_year');
            $maxYear = request('max_year');
            if ($minYear) {
                $minBirthYear = date('Y') - $minYear;
                $q->whereRaw("YEAR(birthday) <= ?", [$minBirthYear]);
            }
            if ($maxYear) {
                $maxBirthYear = date('Y') - $maxYear;
                $q->whereRaw("YEAR(birthday) >= ?", [$maxBirthYear]);
            }
        });
        return $query;
    }

    private static function filterByLanguages($query)
    {
        $query->whereHas('candidate', function ($q) {
            $languages = json_decode(request('languages'), true);
            foreach ($languages as $language) {
                $q->whereJsonContains('languages', [
                    'language' => $language['language'],
                    'rate' => $language['rate']
                ]);
            }
        });
        return $query;
    }

    private static function filterByAddress($query)
    {
        $query->whereHas('candidate', function ($q) {
            $q->where('address', request('address'));
        });
        return $query;
    }

    private static function processResults(Builder $query): Collection
    {
        $test = request('quiz_gruop');
        $take = request('take') ?? 100;
        $minYear = request('min_year');
        $maxYear = request('max_year');

        return $query->get()
            ->filter(function ($chat) use ($minYear, $maxYear) {
                $experience = optional($chat['candidate']['user']['resumes']->first())->experience ?? 0;
                return ($minYear === null || $experience >= $minYear) && ($maxYear === null || $experience <= $maxYear);
            })
            ->map(function ($item) use ($test) {
                if ($test) {
                    $item['result'] = array_filter($item['result'], function ($result) use ($test) {
                        return $result['quizGroup'] == $test;
                    });
                    $item['percentage'] = $item['result'] ? $item['result'][0]['efficiensy'] : 0;
                } else {
                    $item['percentage'] = self::getAveragePercentage($item['result']);
                }
                return $item;
            })
            ->sortByDesc(function ($item) {
                return [
                    $item['percentage'],
                    optional($item['candidate']['user']['resumes']->first())->percentage ?? 0
                ];
            })
            ->take($take)
            ->values();
    }

    private static function paginateResults(Collection $results): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = request('limit') ?? 20;
        $total = $results->count();

        $paginatedResults = $results->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($paginatedResults, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
    }

    private static function getAveragePercentage($tests): float
    {
        $examCount = Exam::count();
        $totalEfficiency = collect($tests)->sum('efficiensy');
        return $examCount > 0 ? round($totalEfficiency / $examCount) : 0;
    }
}
