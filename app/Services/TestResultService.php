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

    public  function show($request)
    {
      $result  = $this->testResultRepository->show($request);

      return $result;
    }

    public function loadOne(array $data, array $mergeData = [], string $encode = 'utf-8')
    {
        // return view('resume.v2', compact('data','mergeData','encode'));
        
        $this->pdf = Pdf::loadView('test.r1', $data, $mergeData, $encode)
            ->setPaper('A4', 'horizontal')
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
