<?php

namespace App\Services;

use App\Constants\ResumeServiceConst;
use TCPDF;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Http\Response;
use JsonException;
use Nette\Utils\Random;

class AdminResumeService extends ResumeServiceConst
{
    protected ?DomPDF $pdf = null;

    /**
     * @param array $data
     * @param array $mergeData
     * @param string $encode
     * @return AdminResumeService
     */
    public function load(array $data, array $mergeData = [], string $encode = 'utf-8')
    {
        // return view('resume.v2', compact('data','mergeData','encode'));
        $this->pdf = PDF::loadView('resume.for_admin', $data, $mergeData, $encode)
            ->setPaper('A4', 'horizontal')
            ->setWarnings(true);
        return $this;
    }

    /**
     * Save PDF file to storage
     *
     * @return AdminResumeService
     * @throws JsonException
     */
    public function save(): AdminResumeService
    {
        if (is_null($this->pdf))
            throw new JsonException('DomPDF not initialized');

        $this->pdf->save(
            base_path('public/uploads/resume/') .
            Random::generate() . '.pdf'
        );

        return $this;
    }

    /**
     * Render and display PDF file
     *
     * @param string $name
     *
     * @return Response
     * @throws JsonException
     */
    public function stream(string $name = 'download.pdf'): Response
    {
        if (is_null($this->pdf))
            throw new JsonException('DomPDF not initialized');

        return $this->pdf->stream($name);
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
