<?php

namespace App\Filters;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
        ->when(request('sex'), self::filterBySex(...))
        ->when(request('spheres'), self::filterBySpheres(...))
        ->when(request('educ_level'), self::filterByEducationLevel(...))
        ->when(request('min_year') || request('max_year'), self::filterByAge(...))
        ->when(request('languages'), self::filterByLanguages(...))
        ->when(request('address'), self::filterByAddress(...));
    }

    private static function filterByTitle($query): void
    {
        $title = request('title');
        if ($title) {
            $query->where(function ($q) use ($title) {
                $q->where('name', 'like', '%' . $title . '%')
                  ->orWhere('surname', 'like', '%' . $title . '%')
                  ->orWhere('specialization', 'like', '%' . $title . '%');
            });
        }
    }

    private static function filterBySex($query): void
    {
        $query->whereHas('candidate', function ($q) {
            $q->where('sex', request('sex'));
        });
    }

    private static function filterBySpheres($query): void
    {
        $query->whereHas('candidate', function ($q) {
            $q->whereJsonContains('spheres', request('spheres'));
        });
    }

    private static function filterByEducationLevel($query): void
    {
        $query->whereHas('candidate', function ($q) {
            $q->where('education_level', request('educ_level'));
        });
    }

    private static function filterByAge($query): void
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
    }

    private static function filterByLanguages($query): void
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
    }

    private static function filterByAddress($query): void
    {
        $query->whereHas('candidate', function ($q) {
            $q->where('address', request('address'));
        });
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
