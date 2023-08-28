<?php

namespace App\Services;

use App\Repository\TestResultRepository;
use Exception;
use Illuminate\Support\Collection;

class TestResultService 
{
   
    protected $testResultRepository;

    public function __construct(TestResultRepository $testResultRepository)
    {
      $this->testResultRepository = $testResultRepository;
    }


    public function getAll($request)
    {
       return $this->testResultRepository->getAll($request);
    } 


    public function store($request) 
    {
        $result  = $this->testResultRepository->store($request);

        return $result;
    }

    public function getCandidateTest($request)
    {
      $result  = $this->testResultRepository->getCandidateTest($request);

      return $result;
          
    }
}
