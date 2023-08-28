<?php

namespace App\Repository;

use App\Models\TestResult;

class TestResultRepository
{
    protected $user;

    public function __construct()
    {
        $this->user = _auth()->user();
    }

    public function getAll($request)
    {
        $data = $this->user->customer->testResult()
                ->with('candidate')
                ->where('deleted_at', null)
                ->paginate($request->limit ?? 15);
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
}
