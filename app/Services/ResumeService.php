<?php

namespace App\Services;

use App\Constants\ResumeServiceConst;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Http\Response;
use JsonException;
use Nette\Utils\Random;

class ResumeService extends ResumeServiceConst
{
    protected ?DomPDF $pdf = null;

    /**
     * @param array $data
     * @param array $mergeData
     * @param string|null $encode
     * @return ResumeService
     */
    public function load(array $data, array $mergeData = [], ?string $encode = null): ResumeService
    {
        $this->pdf = PDF::loadView('resume.v1', $data, $mergeData, $encode)
            ->setPaper('A4', 'horizontal')
            ->setWarnings(true);

        return $this;
    }

    /**
     * Save PDF file to storage
     *
     * @return ResumeService
     * @throws JsonException
     */
    public function save(): ResumeService
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
     * @return Response
     * @throws JsonException
     */
    public function stream(): Response
    {
        if (is_null($this->pdf))
            throw new JsonException('DomPDF not initialized');

        return $this->pdf->stream('download.pdf');
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
