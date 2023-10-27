<?php

namespace App\Repository;

use App\Models\TestResult;

use function Laravel\Prompts\text;

class TestResultRepository
{
    protected $user;

    public function __construct()
    {
        $this->user = _auth()->user();
    }

    public function allResult()
    {
        $test = request('quiz_gruop') ?? null;
     
        $data =  TestResult::with('candidate' , 'candidate.user.resumes')
                ->whereHas('candidate', function ($query) {
                    $query->where('deleted_at', null);
                })
                ->where('deleted_at', null)->get();
      
        $filteredResults = collect($data)->filter(function ($item) use ($test) {
                $sortArr = [];
                $efficiensy = 0;
                foreach($item['result'] as $key => $value){
                    if($test == null){
                        $sortArr[] = $value;
                    } else {
                        if($value['quizGroup'] == $test ){
                            $efficiensy  = $value['efficiensy'];
                            $sortArr[] = $value;
                        }
                    }
                }  
                $max = $test == null ? $this->getAveragePercentage($item['result']) :  $efficiensy  ;
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
                    ->values();
        return $sortedResults ?? null;
    }

   


    public function getAll($request)
    {
        $data = $this->user->customer->testResult()
                ->with('candidate')
                ->whereHas('candidate', function ($query) {
                    $query->where('deleted_at', null);
                })
                ->where('deleted_at', null)
                ->get();
        return $data;        
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
            $result = $this->user->candidate->testResult()->where('deleted_at', null)->where('customer_id', $customer_id)->firstOrFail();

            return $result;
        }
        
        $result = $this->user->candidate->testResult()->where('deleted_at', null)->where('customer_id', null)->firstOrFail();

        return $result;
    }

    public function show($request)
    {
        $result = $this->user->customer->testResult()->with('candidate')
        ->whereHas('candidate', function ($query) {
            $query->where('deleted_at', null);
        })->where('deleted_at', null)->where('id', $request->test_id)->firstOrFail();

        return $result;
    }


    private  function getAveragePercentage($tests) {
        if ($tests) {
            $totalEfficiency = array_reduce($tests, function ($acc, $test) {
                return $acc + $test['efficiensy'];
            }, 0);
            $averagePercentage = round($totalEfficiency / count($tests));
            return $averagePercentage;
        }
    }
}
