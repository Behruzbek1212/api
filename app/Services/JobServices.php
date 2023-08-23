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


    public function createJobBanner($company, $title, $salary, $address, $post_number)
    {
        $randomFileName = uniqid() . '.jpg';
        $imagefileUrl = 'uploads/image/job-posts/' . $randomFileName;
        $storagePath = public_path($imagefileUrl);
        $postNumber =  '№ ' . $post_number ?? 0;
        $text1 = $title;
        $text2 = $company;
        $text3 = Location::find($address)['name']['ru'] ?? "";

        if (isset($salary['agreement']) && $salary['agreement'] !== true) {
            try{
                if (isset($salary['amount']) && $salary['amount'] !== null) {
                    $text4 = explode('-', $salary['amount']);
                } elseif (isset($salary['min_salary']) && $salary['min_salary'] !== null  && isset($salary['max_salary']) && $salary['max_salary'] !== null) {
                    $text4 = explode('-', $salary['min_salary'] . '-' . $salary['max_salary']);
                } elseif(isset($salary['min_salary']) &&  $salary['min_salary'] !== null) {
                    $text4 = explode('-', $salary['min_salary']);
                }elseif(isset($salary['max_salary']) && $salary['max_salary'] !== null){
                    $text4= explode('-',  $salary['max_salary']);
                } 
                try{
                    $formattedParts = array_map(function ($text) {
                        return number_format(trim($text), 0, '', ' ');
                    }, $text4);
                }  catch(Exception $e){
                    $formattedParts = $text4;
                }
               
                // Join the formatted parts back with the '-' character
                $prices = implode(' - ', $formattedParts);
            } catch (Exception $e){
                $prices = $text4;
            }
        } else {
            $prices = 'На основе собеседования';
        }
        // Trim whitespace from each part and add a space as a thousands separator

        $rasmUrl = public_path('img/banner.jpg');
        $font_file = 'Gilroy-ExtraBold.otf';
        $font_path = public_path('fonts/' . $font_file);

        $font_path = realpath($font_path);

        $font_path = mb_convert_encoding($font_path, 'big5', 'utf-8');

        $gilroyLight = public_path('fonts/Gilroy-Light.otf');
        $gilroyLight = realpath($gilroyLight);

        $gilroyLight = mb_convert_encoding($gilroyLight, 'big5', 'utf-8');

        $jpg_image = Image::make($rasmUrl);

        $green = [10, 180, 93];



        // Custom text generator to wrap the text based on the maximum width
        $lines = wordwrap($text1, 42, "\n", true);



        $words = explode(' ', $text1); // Matnni so'zlarga bo'lib massivga ajratamiz
        $wordCount = count($words);

        // If there are more than 5 words, trim the string and add three dots at the end
        if ($wordCount > 4) {
            $words = explode(' ', $lines);
            $trimmedString = implode(' ', array_slice($words, 0, 4));
            $trimmedString .= '...';
        } else {
            $trimmedString = $lines;
        }

        $firstNewLinePos = strpos($trimmedString, "\n");

        if ($firstNewLinePos !== false) {
            $firstLine = substr($trimmedString, 0, $firstNewLinePos);
            $remainingText = substr($trimmedString, $firstNewLinePos + 1);
        } else {
            $firstLine = $trimmedString;
            $remainingText = "";
        }

        $jpg_image->text($postNumber,  753, 273, function ($font) use ($font_path) {
            $font->file($font_path);
            $font->size(50);
            $font->color('#0079fe');
            $font->align('center');
            $font->valign('middle');
        });

        $jpg_image->text($firstLine, 750, 450, function ($font) use ($font_path, $green) {
            $font->file($font_path);
            $font->size(92);
            $font->color($green);
            $font->align('center');
            $font->valign('middle');
        });
        if ($remainingText !== "") {
            $jpg_image->text($remainingText, 750, 560, function ($font) use ($font_path, $green) {
                $font->file($font_path);
                $font->size(92);
                $font->color($green);
                $font->align('center');
                $font->valign('middle');
            });
        }


        $jpg_image->text($text2, 330, 1080, function ($font) use ($gilroyLight) {
            $font->file($gilroyLight);
            $font->size(40);
            $font->color('#7c7c7c');
            $font->align('left');
            $font->valign('middle');
        });

        $jpg_image->text($text3, 1030, 1080, function ($font) use ($gilroyLight) {
            $font->file($gilroyLight);
            $font->size(40);
            $font->color('#7c7c7c');
            $font->align('left');
            $font->valign('middle');
        });

        $jpg_image->text($prices,  750, 920, function ($font) use ($font_path) {
            $font->file($font_path);
            $font->size(70);
            $font->color('#0079fe');
            $font->align('center');
            $font->valign('middle');
        });
        $jpg_image->save($storagePath);


        return 'https://static.jobo.uz/' .   $imagefileUrl;
    }
}
