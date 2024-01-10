<?php

namespace App\Repository;

use App\Filters\RatingFilter;
use App\Models\TestResult;


use Illuminate\Pagination\LengthAwarePaginator;
class TestResultRepository
{
    protected $user;

    public function __construct()
    {
        $this->user = _auth()->user();
    }

    public function allResult()
    {
        $data =  TestResult::with('candidate' , 'candidate.user.resumes');
                
        $filter = RatingFilter::apply($data);
      
        return $filter;
    }
    
    public function candidateRatings()
    {   
      
        $user = $this->user->id;
        $data = TestResult::with('candidate', 'candidate.user.resumes')
                ->whereHas('candidate', function ($query) {
                    $query->where('deleted_at', null);
                })
                ->where('deleted_at', null)->get();

        $filteredResults = collect($data)->filter(function ($item) {
                    $sortArr = [];
                    $efficiensy = 0;
                    $max = self::getAveragePercentage($item['result']);
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
        })->values();
        
        $filteredResults = $sortedResults->map(function ($item, $key) use ($user) {
                if($item['candidate']['user']['id'] === $user){
                    return $key + 1;
                }
        })->filter(function ($item) {
            return $item !== null; // Remove the items that are not null (i.e., where the condition was not met)
        })->values();
        
        return $filteredResults->first() ?? null;
    }
   
    public function getAll($request)
    {
        $users = _auth()->user();

        if ($users !== null) {
            if($users->customer !== null){
                $data = $users->customer->testResult()
                ->with('candidate')
                ->whereHas('candidate', function ($query) {
                    $query->where('deleted_at', null);
                })
                ->where('deleted_at', null)
                ->get();

            return $data ?? [];
            }
            return [];
        } else {
            return []; // or handle the case where the user is null
        }
    }
    public function store($request) 
    {
        $user = $this->user;
        $customer_id = $request->get('customer_id') ?? null;
        $data = $request->get('data');
        if($customer_id !== null){
          $testRes = TestResult::query()->where('candidate_id', $user->candidate->id)->where('customer_id', $customer_id)->first();
        } else {
          $testRes = TestResult::query()->where('candidate_id', $user->candidate->id)->where('customer_id', null)->first();
        }

        if($testRes == null) {
            $store = TestResult::query()->create([
                'candidate_id' => $user->candidate->id,
                'customer_id' => $customer_id ,
                'result' => [$data]
            ]);
            return $store;
        } else {
            $test = $testRes->result;
            
            foreach ($test as $testkey) {
                if ($testkey['quizGroup'] === $data['quizGroup']) {
                    return [];
                }    
            }
         
            $test[] = $data;
           
            $testRes->result = $test;
            
            $testRes->save();

            return $testRes;
        
        }
    }

    public function getCandidateTest($request)
    {
        $customer_id = $request->customer_id ?? null;

        if($customer_id !== null){
            if($this->user->candidate !== null){
                $result = $this->user->candidate->testResult()->where('deleted_at', null)->where('customer_id', $customer_id)->first();

                return $result ?? [];
            }
            return [];
        }
        if($this->user->candidate !== null){
            $result = $this->user->candidate->testResult()->where('deleted_at', null)->where('customer_id', null)->first();

            return $result ?? [];
        }
        return [];
    }

    public function show($request)
    {
        $result = $this->user->customer->testResult()->with('candidate')
        ->whereHas('candidate', function ($query) {
            $query->where('deleted_at', null);
        })->where('deleted_at', null)->where('id', $request->test_id)->firstOrFail();

        return $result;
    }
    
    private static function getAveragePercentage($tests)
    {
        if ($tests) {
            $totalEfficiency = array_reduce($tests, function ($acc, $test) {
                return $acc + $test['efficiensy'];
            }, 0);
            $averagePercentage = round($totalEfficiency / count($tests));
            return $averagePercentage;
        }
    }

    
}
