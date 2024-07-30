<?php

namespace App\Filters;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RatingFilter
{
    public static function apply($query)
    {
        $test = request('quiz_gruop');
        $take = request('take', 100);
        $title = request('title');
        $min_year = request('min_year');
        $max_year = request('max_year');

        $query->with(['candidate.user.resumes'])
            ->whereHas('candidate', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->whereNull('deleted_at');

        self::applyFilters($query, $title, $min_year, $max_year);

        $perPage = request('limit', 20);
        $page = request('page', 1);

        $paginatedResults = $query->paginate($perPage, ['*'], 'page', $page);

        $processedResults = $paginatedResults->getCollection()->map(function ($item) use ($test) {
            return self::processItem($item, $test);
        })->filter(function ($item) {
            return $item['percentage'] > 0;
        })->sortByDesc(function ($item) {
            return [
                $item['percentage'],
                $item['candidate']['user']['resumes']->isNotEmpty() ? 1 : 0,
                $item['candidate']['user']['resumes']->first()['percentage'] ?? 0
            ];
        })->take($take)->values();

        $paginatedResults->setCollection($processedResults);

        return $paginatedResults;
    }

    private static function applyFilters($query, $title, $min_year, $max_year)
    {
        if ($title) {
            $query->whereHas('candidate', function ($q) use ($title) {
                $q->where(function ($subq) use ($title) {
                    $subq->where('name', 'like', "%$title%")
                         ->orWhere('surname', 'like', "%$title%")
                         ->orWhere('specialization', 'like', "%$title%");
                });
            });
        }

        if (request('sex')) {
            $query->whereHas('candidate', function ($q) {
                $q->where('sex', request('sex'));
            });
        }

        if (request('spheres')) {
            $query->whereHas('candidate', function ($q) {
                $q->whereJsonContains('spheres', request('spheres'));
            });
        }

        if (request('educ_level')) {
            $query->whereHas('candidate', function ($q) {
                $q->where('education_level', request('educ_level'));
            });
        }

        if (request('min_age') || request('max_age')) {
            $query->whereHas('candidate', function ($q) {
                $minAge = request('min_age');
                $maxAge = request('max_age');
                if ($maxAge === null) {
                    $q->whereRaw("YEAR(birthday) <= YEAR(CURDATE()) - ?", [$minAge]);
                } elseif ($minAge === null) {
                    $q->whereRaw("YEAR(birthday) >= YEAR(CURDATE()) - ?", [$maxAge]);
                } else {
                    $minYear = date('Y') - $minAge;
                    $maxYear = date('Y') - $maxAge;
                    $q->whereBetween(DB::raw('YEAR(birthday)'), [$maxYear, $minYear]);
                }
            });
        }

        if (request('languages')) {
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

        if (request('address')) {
            $query->whereHas('candidate', function ($q) {
                $q->where('address', request('address'));
            });
        }

        self::applyExperienceFilter($query, $min_year, $max_year);
    }

    private static function applyExperienceFilter($query, $min_year, $max_year)
    {
        if ($min_year !== null || $max_year !== null) {
            $query->whereHas('candidate.user.resumes', function ($q) use ($min_year, $max_year) {
                $min_months = self::yearToMonths($min_year);
                $max_months = self::yearToMonths($max_year);

                if ($min_months !== null && $max_months !== null) {
                    $q->whereBetween('experience', [$min_months, $max_months]);
                } elseif ($min_months !== null) {
                    $q->where('experience', '>=', $min_months);
                } elseif ($max_months !== null) {
                    $q->where('experience', '<=', $max_months);
                }
            });
        }
    }

    private static function yearToMonths($year)
    {
        if ($year === null) return null;
        return strpos($year, '0.') !== false ? intval(str_replace('0.', '', $year)) : intval($year * 12);
    }

    private static function processItem($item, $test)
    {
        if ($test === null) {
            $item['percentage'] = intval(self::getAveragePercentage($item['result']));
        } else {
            $testResult = collect($item['result'])->firstWhere('quizGroup', $test);
            $item['percentage'] = $testResult ? intval($testResult['efficiensy']) : 0;
            $item['result'] = $testResult ? [$testResult] : [];
        }
        return $item;
    }

    private static function getAveragePercentage($tests)
    {
        static $examCount = null;
        if ($examCount === null) {
            $examCount = Exam::count();
        }

        if ($tests && $examCount > 0) {
            $totalEfficiency = array_sum(array_column($tests, 'efficiensy'));
            return round($totalEfficiency / $examCount);
        }
        return 0;
    }
}
