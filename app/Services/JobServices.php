<?php

namespace App\Services;

use App\Filters\JobFilter;
use App\Models\Job;
use App\Models\Location;
use App\Models\Trafic;
use App\Repository\JobRepository;
use App\Traits\HasScopes;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class JobServices
{
    use HasScopes;
    public $repository;

    public function __construct(JobRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): JobServices
    {
        return new static(JobRepository::getInctance());
    }

    public function dropTrafic()
    {

        Job::whereNotNull('trafic_id')
            // ->whereHas('trafic', function ($query) {
            //     $query->whereIn('type', Trafic::NOT_DROP_TYPE);
            // })
            ->where('trafic_expired_at', '<=', date('Y-m-d H:i:s'))
            ->update([
                'trafic_id' => null,
                'trafic_expired_at' => null,
            ]);

        Http::withOptions(['verify' => false])->post('https://api.telegram.org/bot5777417067:AAGvh21OUGVQ7nmSnLbIhzTiZxoyMQMIZKk/sendMessage', [
            'chat_id' => '-1001821241273',
            'text' => "title: cron ishladi"
        ]);
        return true;
    }

    public function list($request)
    {
        $jobs =  new JobFilter($request);
        return $this->repository->list(function (Builder $builder) use ($jobs) {
            return $builder
                ->with('trafic')
                ->join('trafics', 'jobs.trafic_id', '=', 'trafics.id', 'left')
                // ->where('trafics.key', Trafic::KEY_FOR_SITE)
                ->orderBy('trafics.type', 'DESC')
                ->orderBy('trafics.vip_day', 'DESC')
                ->orderBy('trafics.count_rise', 'DESC')
                ->orderBy('id', 'DESC')
                ->select('jobs.*')
                ->filter($jobs);
        });
    }


    public function companiesJobs($request)
    {
        $jobs = Job::query()
            ->where('customer_id', $request->customer_id)
            ->where('deleted_at', null)
            ->orderByDesc('updated_at')
            ->paginate($request->limit ?? 8);
        return $jobs;
    }


    public function createJobBanner($company, $title, $salary, $address, $postNumber = 0, $bonus = false)
    {

        $randomFileName = uniqid() . '.jpg';
        $imageFileUrl = 'uploads/image/job-posts/' . $randomFileName;
        $storagePath = public_path($imageFileUrl);
        $postNumber = '№ ' . ($postNumber ?? 0);
        $text1 = $title;
        $text2 = $company;
        $text3 = Location::find($address)['name']['uz'] ?? "";

        $prices = $this->getSalaryText($salary);

        $rasmUrl = public_path('img/banner.jpg');
        $fontFile = 'MYRIADPRO-BOLDCONDIT.OTF';
        $fontPath = $this->getFontPath('fonts/' . $fontFile);

        $gilroyLight = $this->getFontPath('fonts/MYRIADPRO-BOLDCOND.OTF');

        $jpgImage = Image::make($rasmUrl);
        $green = '#3894FF';

        $lines = wordwrap($text1, 28, "\n", true);
        $words = explode(' ', $text1);
        $wordCount = count($words);

        $trimmedString = $wordCount > 4 ? $this->getTrimmedString($lines, 4) : $lines;

        list($firstLine, $remainingText) = $this->getFirstAndRemainingLines($trimmedString);

        // $jpgImage->text($postNumber, 1010, 365, function ($font) use ($fontPath) {
        //     $font->file($fontPath);
        //     $font->size(70);
        //     $font->color('#057AF5');
        //     $font->align('center');
        //     $font->valign('middle');
        // });

        $positionY = $remainingText !== "" ? 595 : 775;

        $jpgImage->text($firstLine, 988, $positionY, function ($font) use ($fontPath, $green) {
            $font->file($fontPath);
            $font->size(130);
            $font->color('#057AF5');
            $font->align('center');
            $font->valign('middle');
        });

        if ($remainingText !== "") {
            $jpgImage->text($remainingText, 988, 790, function ($font) use ($fontPath, $green) {
                $font->file($fontPath);
                $font->size(114);
                $font->color('#057AF5');
                $font->align('center');
                $font->valign('middle');
            });
        }

        $jpgImage->text($text2, 220, 1442, function ($font) use ($gilroyLight) {
            $font->file($gilroyLight);
            $font->size(72);
            $font->color('#c8a844');
            $font->align('left');
            $font->valign('middle');
        });

        $jpgImage->text($text3, 200, 480, function ($font) use ($gilroyLight) {
            $font->file($gilroyLight);
            $font->size(60);
            $font->color('#262626');
            $font->align('left');
            $font->valign('middle');
        });

        $posY = $bonus ? 1118 : 1118;
        $jpgImage->text($prices, 988, $posY, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(108);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        if ($bonus) {
            $jpgImage->text("+ bonus", 988,  1290, function ($font) use ($fontPath) {
                $font->file($fontPath);
                $font->size(100);
                $font->color('#057AF5');
                $font->align('center');
                $font->valign('middle');
            });
        }

        $jpgImage->encode('jpg', 100)->save($storagePath);

        return 'https://static.jobo.uz/' . $imageFileUrl;
    }

    public function getSalaryText($salary)
    {
        if (isset($salary['agreement']) && $salary['agreement'] !== true) {
            try {
                $text4 = $this->extractSalaryRange($salary);
                $formattedParts = $this->formatSalaryParts($text4, $salary['currency']);

                $prices = implode(' - ', $formattedParts);
                if (count($formattedParts) === 1) {
                    $prices = isset($salary['min_salary']) && $salary['min_salary'] !== null
                        ?  $prices .' dan'
                        :  $prices . ' gacha';
                } else {
                    $formattedParts[0] =  $formattedParts[0] . " dan" ;
                }

            } catch (Exception $e) {
                $prices = $text4;
            }
        } else {
            $prices = 'Suhbat asosida';
        }

        return $prices;
    }

    public function extractSalaryRange($salary)
    {
        if (isset($salary['min_salary']) && $salary['min_salary'] !== null && isset($salary['max_salary']) && $salary['max_salary'] !== null) {
            $text4 = explode('-', $salary['min_salary'] . '-' . $salary['max_salary']);
        } elseif (isset($salary['min_salary']) && $salary['min_salary'] !== null) {
            $text4 = explode('-', $salary['min_salary']);
        } elseif (isset($salary['max_salary']) && $salary['max_salary'] !== null) {
            $text4 = explode('-', $salary['max_salary']);
        } elseif (isset($salary['amount']) && $salary['amount'] !== null) {
            $text4 = explode('-', $salary['amount']);
        }

        return $text4;
    }

    public function formatSalaryParts($text4, $currency)
    {
        return array_map(function ($text) use ($currency) {
            if ($currency == 'USD') {
                return number_format(trim($text), 0, '', ' ') . "$";
            } else {
                return number_format(trim($text), 0, '', ' ');
            }
        }, $text4);
    }

    public function getTrimmedString($lines, $maxWords = 4)
    {
        $words = explode(' ', $lines);
        $trimmedString = implode(' ', array_slice($words, 0, $maxWords));
        $trimmedString .= (count($words) > $maxWords) ? '...' : '';

        return $trimmedString;
    }

    public function getFirstAndRemainingLines($trimmedString)
    {
        $firstNewLinePos = strpos($trimmedString, "\n");

        if ($firstNewLinePos !== false) {
            $firstLine = substr($trimmedString, 0, $firstNewLinePos);
            $remainingText = substr($trimmedString, $firstNewLinePos + 1);
        } else {
            $firstLine = $trimmedString;
            $remainingText = "";
        }

        return [$firstLine, $remainingText];
    }

    public function getFontPath($fontPath)
    {
        $fontPath = realpath(public_path($fontPath));
        $fontPath = mb_convert_encoding($fontPath, 'big5', 'utf-8');

        return $fontPath;
    }
}
