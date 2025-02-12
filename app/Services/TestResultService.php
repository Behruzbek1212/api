<?php

namespace App\Services;

use App\Repository\TestResultRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Collection;
use JsonException;

class TestResultService 
{
    protected ?Dompdf $pdf = null;
    protected $testResultRepository;

    public function __construct(TestResultRepository $testResultRepository)
    {
      $this->testResultRepository = $testResultRepository;
    }

    public function allTestCount()
    {
        return $this->testResultRepository->allResult();
    }
    
    public function candidateRatingsServer()
    {   
        
        $datas =  $this->testResultRepository->candidateRatings();
        return $datas;
        
    }


    public function getAll($request)
    {   
       $data = $this->testResultRepository->getAll($request);
       return $data  == null ? [] : $data;
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

    public  function show($request)
    {
      $result  = $this->testResultRepository->show($request);

      return $result;
    }

    public function loadOne(array $data, array $mergeData = [], string $encode = 'utf-8')
    {
      
        $this->pdf = Pdf::loadView('test.r1', $data, $mergeData, $encode)
            ->setPaper('A4', 'horizontal')
            ->setWarnings(true);
        return $this;
    }
    public function loadCustomer(array $data, array $mergeData = [], string $encode = 'utf-8')
    {
        $customPaper = array(0,0,1000,1620.80);
        $this->pdf = Pdf::loadView('test.r2', $data, $mergeData, $encode)
            ->setPaper($customPaper, 'landscape')
            // ->setOptions([
            //   'dpi' => 126.6, 
            // ])
            ->setWarnings(true);
        return $this;
    }


     /**
     * Render and download PDF file
     *
     * @param string $name
     *
     * @return Response
     * @throws JsonException
     */
    public function download(string $name = 'download.pdf'): Response
    {
        if (is_null($this->pdf))
            throw new JsonException('DomPDF not initialized');

        return $this->pdf->download($name);
    }

 
}
