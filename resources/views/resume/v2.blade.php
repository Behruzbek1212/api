@extends('app')

@php
    /**
     * @var \App\Models\Candidate|\Illuminate\Contracts\Auth\Authenticatable $candidate
     * @var array $data
     * @var int $resume_id
     * @var int $experience
     * @var string $level
     */

    // app()->setLocale('ru');
    app()->setlocale(request('lang') ?? 'ru');
    // @dd(app()->getLocale());

    $faker = \Faker\Factory::create();

    $candidate_page = 'https://jobo.uz/candidates/';
    $show_resume_page = 'https://api.jobo.uz/v1/resume/show/';

    // Blue colored generator
    // $qrcode = qrcode(50)->color(0, 121, 254)->generate($candidate_page . $candidate->id);

    if (! function_exists('calculateYearsAndMonths')) {
        function calculateYearsAndMonths($months):string {
          $years = floor($months / 12);
          $remainingMonths = $months % 12;

          if ($years > 0 && $remainingMonths > 0) {
            return $years . " " . __('resume.dates.year')." " . $remainingMonths . " " . __('resume.dates.month');
          } elseif ($years > 0) {
            return $years . " year(s)";
          } else {
            return $remainingMonths . " " . __('resume.dates.month');
          }
        }
    }

    $all_experience = calculateYearsAndMonths($experience);

    if (! function_exists('calculateEmployment')) {
        function calculateEmployment($dates):int {
          $exp_time = 0;
          $employment = $dates;

          $start_year = $employment['date']['start']['year'] * 1;
          $start_month = $employment['date']['start']['month'] * 1;

          $end_year = $employment['date']['end']['year'] * 1 ?? 0;
          $end_month = $employment['date']['end']['month'] * 1 ?? 0;

          if (@$employment['date']['present'] === true) {
            $end_year = date('Y');
            $end_month = date('m');
          }

          $exp_time += ($end_year - $start_year) * 12;
          $exp_time += $end_month - $start_month;

          return $exp_time;
        }
    }

    if (! function_exists('showEducationLevels')) {
        function showEducationLevels($level):string {
            if ($level !== 'higher' && $level !== 'secondary' && $level !== 'incomplete_higher') {
                return (string)$level;
            }
            $temp = 'resume.education_levels.' . $level;
            $str = __($temp);
            return $str !== null ? $str : "No Education Level";
        }
    }

    // Dark colored generator
    $qrcode = qrcode(50)->color(89, 89, 89)->generate($show_resume_page . $resume_id);

    $avatar = str_replace("https://static.jobo.uz/", "", $candidate->avatar);
    $avatar = public_path($avatar);

    if (! file_exists($avatar))
        throw new \ErrorException('File does not exist');
@endphp

@section('header')
    <meta charset="utf-8">
@endsection

@section('title', $candidate->name)

@section('style')
    <style id="fonts" type="text/css">
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: url({{ storage_path("fonts/Roboto-Regular.ttf") }}) format("truetype");
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 600;
            src: url({{ storage_path("fonts/Roboto-Medium.ttf") }}) format("truetype");
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: url({{ storage_path("fonts/Gilroy-Bold.ttf") }}) format("truetype");
        }
    </style>

    <style id="header" type="text/css">
        header {
            position: fixed;
            inset: -50px -40px auto;
        }

        header span {
            background-color: #0079FE;
            position: absolute;
            inset: 10px 0 auto;
            height: 20px;
        }

        header img {
            position: relative;
            padding: 20px;
            margin-left: 40px;
            width: 80px;
            background-color: #FFFFFF;
            z-index: 10;
        }
    </style>

    <style id="footer" type="text/css">
        footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;
        }

        footer a {
            color: #0079FE;
            font-weight: 400;
            text-decoration: none;
        }
    </style>

    <style id="normalizer" type="text/css">
        @page {
            margin: 60px 40px;
            font-family: Roboto, sans-serif;
        }

        h1, h2, h3, h4, h5, h6, p {
            padding: 0;
            margin: 0;
            color: #595959;
            font-family: Roboto, sans-serif;
        }

        table {
            z-index: 50;
        }
        /*table tr td, table tr th {*/
        /*    page-break-inside: avoid !important;*/
        /*}*/
        /*table { page-break-inside:auto }*/
        /*tr    { page-break-inside:avoid; page-break-after:auto }*/
        /*thead { display:table-header-group }*/
        /*tfoot { display:table-footer-group }*/
    </style>

    <style id="components" type="text/css">
        .unstyled {
            color: #595959;
            font-weight: 400;
            text-decoration: none;
        }

        .text-blue {
            color: #0079FE;
        }

        .text-sm {
            font-size: 14px;
        }

        .text-md {
            font-size: 16px;
        }

        .mb-4 {
            margin-bottom: 8px;
        }

        .w-full {
            width: 100%;
        }

        .font-normal {
            font-weight: 400;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .relative {
            position: relative;
        }

        .profile-avatar {
            border-radius: 9999px;
        }

        .space-1 > * ~ * {
            margin-top: 2px;
        }

        .splitter {
            width: 100%;
            height: 2px;
            margin-top: 15px;
            margin-bottom: 15px;
            background-color: #0079FE;
        }

        .table-row > td ~ td {
            padding-left: 20px;
        }

        .table-space-none {
            border-spacing: 0;
        }

        .candidate-name {
            font-weight: 700;
            font-size: 38px;
        }

        .left-side {
            max-width: 170px;
            min-width: 170px;
            vertical-align: top;
        }

        .right-side {
            font-size: 14px;
            vertical-align: top
        }
    </style>

    <style id="timeline" type="text/css">
        .timeline:last-child td {
            position: relative;
        }

        .timeline:last-child td span.tl-fixer {
            width: 4px;
            position: absolute;
            inset: 13px auto 0px 2px;
            background-color: #FFFFFF;
        }

        .timeline-line {
            width: 2px;
            position: absolute;
            inset: 58px auto 0px 6px;
            background-color: #0079FE
        }
        .timeline-line.experience{
            inset: 78px auto 0px 6px;
        }

        .timeline-dot {
            width: 6px;
            height: 6px;
            background-color: #0079FE;
            border-radius: 9999px;
            margin-right: 2px;
        }
    </style>

    <style type="text/css">
        #experience{
            margin-top: 25px;
        }
        .about_container{
            margin-top: 55px;
            margin-left: 7px;
        }
    </style>
@endsection

@section('content')
    <header>
        <img src="{{ public_path('assets/logo.png') }}" alt="Logo" />
        <span></span>
    </header>

    <footer>
        <table class="w-full table-space-none">
            <tr class="w-full">
                <td class="space-1">
                    <a href="https://jobo.uz" class="text-blue">www.jobo.uz</a>
                </td>
                <td align="right">
                    <img src="data:image/svg+xml;base64,{{ base64_encode($qrcode) }}" />
                </td>
            </tr>
        </table>
    </footer>

    <main id="root">
        <table class="w-full">
            <tr class="w-full">
                <td class="space-1">
                    <h1 class="candidate-name">{{ $candidate->name . ' ' . @$candidate->surname }}</h1>
                    <p class="text-sm"><span class="font-semibold">{{ __('resume.email') }}:</span> {{ $candidate->email }}</p>
                    <p class="text-sm"><span class="font-semibold">{{ __('resume.phone') }}:</span> <a href="tel:{{ $candidate->phone }}" class="unstyled">{{ $candidate->phone }}</a></p>
                    <p class="text-sm"><span class="font-semibold">{{ __('resume.birthday') }}: </span> {{ date('Y.m.d', strtotime($candidate->birthday)) }}</p>
                    <p class="text-sm"><span class="font-semibold">{{ __('resume.address') }}:</span> {{ $candidate->location }}</p>
                </td>

                @if( !str_contains($avatar, 'default.webp') && !str_contains($avatar, 'avatar.png') )
                    <td style="width: 175px; padding-left: 25px">
                        <img
                            src="{{ $avatar }}"
                            alt="{{ $candidate->name }}"
                            class="profile-avatar"
                            width="100%"
                        />
                    </td>
                @endif
            </tr>
        </table>

        <table id="desired-jobs-and-salary" class="w-full">
            <tr class="w-full table-row">
                <td class="left-side">
                    <p class="font-bold">{{ __('resume.list.desired') }}</p>
                </td>
                <td class="w-full right-side">
                    <div class="splitter"></div>
                    <table id="salary-section" class="w-full table-space-none">
                        <tr class="w-full">
                            <td>
                                <p class="text-md">
                                    <span class="font-bold">{{ __('resume.message.position') }}:</span>
                                    {{ $data['position'] }}
                                </p>
                            </td>
                            @if( !$data['hide_salary'] )
                                <td>
                                    <p class="font-bold text-md" align="right">
                                        {{
                                            @$data['salary']['agreement'] ?
                                                __('resume.message.agreement') :
                                                $data['salary']['amount'] . ' ' . __('currency.' . $data['salary']['currency'])
                                        }}
                                    </p>
                                </td>
                            @endif
                        </tr>
                    </table>

                    <p class="text-sm">{{ __('resume.message.sphere') }}: {{ __('sphere.' . @$data['sphere']) }}</p>
                    <p class="text-sm">{{ __('resume.message.location') }}: {{ $candidate->location }}</p>
                    <p class="text-sm">{{ __('resume.message.type') }}: {{ __('resume.types.' . @$data['work_type']) }}</p>
                </td>
            </tr>
        </table>

        @if(count($data['employment']))
            <table id="experience" class="w-full relative">
                @if(count($data['employment']) > 1)
                    <div class="timeline-line experience"></div>
                @endif
                <tr class="w-full table-row">
                    <td class="left-side">
                        <p class="font-bold">{{ __('resume.list.experience') }}</p>
                    </td>
                    <td class="w-full right-side">
                        <p class="font-bold">{{ $all_experience }}</p>
                        <div class="splitter"></div>
                    </td>
                </tr>
                @foreach($data['employment'] as $employment)
                    <tr class="w-full table-row timeline">
                        <td class="left-side">
                            @if(count($data['employment']) > 1)
                                <span class="tl-fixer"></span>
                            @endif
                            <table id="experience-timeline" class="w-full table-space-none">
                                <tr class="w-full">
                                    @if(count($data['employment']) > 1)
                                        <td>
                                            <div class="timeline-dot"></div>
                                        </td>
                                    @endif
                                    <td>
                                        <p style="font-size: 10px">
                                            {{
                                                __('month.' . $employment['date']['start']['month']) . ' ' .
                                                $employment['date']['start']['year']
                                            }}
                                            &mdash;
                                            @if(!@$employment['date']['present'])
                                                {{ __('month.' . $employment['date']['end']['month']) . ' ' . $employment['date']['end']['year'] }}
                                            @else
                                                {{
                                                    $employment['date']['present'] ?
                                                        __('resume.message.present') :
                                                        __('month.' . $employment['date']['end']['month']) . ' ' . $employment['date']['end']['year']
                                                }}
                                            @endif
                                        </p>
                                        <p style="font-size: 10px" class="font-semibold">
                                            {{
                                              calculateYearsAndMonths(calculateEmployment($employment))
                                            }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="w-full right-side">
                            <h3 class="font-bold text-md">{{ strip_tags($employment['employer']) }}</h3>
                            <p class="text-sm mb-4">{{ strip_tags($employment['title']) }}</p>

                            <p class="text-sm mb-4">{{ str_replace(['&nbsp;', '&amp;'], [' ', '&'], strip_tags($employment['description'])) }}</p>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

        @if(count($data['education']))
            <table id="education" class="w-full relative">
                @if(count($data['education']) > 1)
                    <div class="timeline-line"></div>
                @endif
                <tr class="w-full table-row">
                    <td class="left-side">
                        <p class="font-bold">{{ __('resume.list.education') }}</p>
                    </td>
                    <td class="w-full right-side">
                        <div class="splitter"></div>
                    </td>
                </tr>
                @foreach($data['education'] as $education)
                    <tr class="w-full table-row timeline">
                        <td class="left-side">
                            @if(count($data['education']) > 1)
                                <span class="tl-fixer"></span>
                            @endif
                            <table id="experience-timeline" class="w-full table-space-none">
                                <tr class="w-full">
                                    @if(count($data['education']) > 1)
                                        <td>
                                            <div class="timeline-dot"></div>
                                        </td>
                                    @endif
                                    <td>
                                        <p style="font-size: 10px">
                                            {{
                                                __('month.' . $education['date']['start']['month']) . ' ' .
                                                $education['date']['start']['year']
                                            }}
                                            &mdash;
                                            @if(!@$education['date']['present'])
                                                {{ __('month.' . $education['date']['end']['month']) . ' ' . $education['date']['end']['year'] }}
                                            @else
                                                {{
                                                    $education['date']['present'] ?
                                                        __('resume.message.present') :
                                                        __('month.' . $education['date']['end']['month']) . ' ' . $education['date']['end']['year']
                                                }}
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="w-full right-side">
                            <h3 class="font-bold text-md">{{ strip_tags($education['school']) }}
                                <span class="text-sm mb-4 font-normal">{{ showEducationLevels($education['degree']) }}</span>
                            </h3>

                            <p class="text-sm mb-4">{{ str_replace(['&nbsp;', '&amp;'], [' ', '&'], strip_tags($education['description'])) }}</p>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

        @if(@$data['additional_education'] && count($data['additional_education']))
            <table id="education" class="w-full relative">
                @if(count($data['additional_education']) > 1)
                    <div class="timeline-line"></div>
                @endif
                <tr class="w-full table-row">
                    <td class="left-side">
                        <p class="font-bold">{{ __('resume.list.additional_education') }}</p>
                    </td>
                    <td class="w-full right-side">
                        <div class="splitter"></div>
                    </td>
                </tr>
                @foreach($data['additional_education'] as $education)
                    <tr class="w-full table-row timeline">
                        <td class="left-side">
                            @if(count($data['additional_education']) > 1)
                                <span class="tl-fixer"></span>
                            @endif
                            <table id="experience-timeline" class="w-full table-space-none">
                                <tr class="w-full">
                                    @if(count($data['additional_education']) > 1)
                                        <td>
                                            <div class="timeline-dot"></div>
                                        </td>
                                    @endif
                                    <td>
                                        <p style="font-size: 10px">
                                            {{
                                                __('month.' . $education['date']['start']['month']) . ' ' .
                                                $education['date']['start']['year']
                                            }}
                                            &mdash;
                                            @if(!@$education['date']['present'])
                                                {{ __('month.' . $education['date']['end']['month']) . ' ' . $education['date']['end']['year'] }}
                                            @else
                                                {{
                                                    $education['date']['present'] ?
                                                        __('resume.message.present') :
                                                        __('month.' . $education['date']['end']['month']) . ' ' . $education['date']['end']['year']
                                                }}
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="w-full right-side">
                            <h3 class="font-bold text-md">{{ strip_tags($education['school']) }}</h3>

                            <p class="text-sm mb-4">{{ str_replace(['&nbsp;', '&amp;'], [' ', '&'], strip_tags($education['description'])) }}</p>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

        @if($candidate['languages'] && count($candidate['languages']))
            <table id="desired-jobs-and-salary" class="w-full">
                <tr class="w-full table-row">
                    <td class="left-side">
                        <p class="font-bold">{{ __('resume.list.language') }}</p>
                    </td>
                    <td class="w-full right-side">
                        <div class="splitter"></div>

                        @foreach($candidate['languages'] as $language)
                            <table class="w-full">
                                <tr class="table-row w-full">
                                    <td class="right-side">
                                        <h3 class="font-bold text-md">{{ __('resume.languages.' . @$language['language']) }}: {{ strtoupper(strip_tags($language['rate'])) }} </h3>
                                    </td>
                                </tr>
                            </table>
                        @endforeach

                    </td>
                </tr>
            </table>
        @endif

        @if(@$data['skills'] && count($data['skills']))
            <table id="desired-jobs-and-salary" class="w-full">
                <tr class="w-full table-row">
                    <td class="left-side">
                        <p class="font-bold">{{ __('resume.list.skills') }}</p>
                    </td>
                    <td class="w-full right-side">
                        <div class="splitter"></div>

                        <p>{{ join(', ', $data['skills']) }}</p>
                    </td>
                </tr>
            </table>
        @endif

        @if(@$data['computer_skills'] && count($data['computer_skills']))
            <table id="desired-jobs-and-salary" class="w-full">
                <tr class="w-full table-row">
                    <td class="left-side">
                        <p class="font-bold">{{ __('resume.list.computer_skills') }}</p>
                    </td>
                    <td class="w-full right-side">
                        <div class="splitter"></div>

                        <p>{{ join(', ', $data['computer_skills']) }}</p>
                    </td>
                </tr>
            </table>
        @endif

        @php
            $arr = [];
            $driving_exp = false;

            if (@$data['driving_experience'])
                $arr = array_filter($data['driving_experience']['categories_of_driving'], function ($value) {
                    return $value == true;
                });

            if (count($arr))
                $driving_exp = true;
        @endphp

        @if($driving_exp)
            <table id="desired-jobs-and-salary" class="w-full">
                <tr class="w-full table-row">
                    <td class="left-side">
                        <p class="font-bold">{{ __('resume.list.driving_experience') }}</p>
                    </td>
                    <td class="w-full right-side">
                        <div class="splitter"></div>

                        <p>{{ __('resume.message.categories_of_driving') }} {{ join(", ", array_keys($arr)) }}</p>
                    </td>
                </tr>
            </table>
        @endif

        @if(@$data['about'])
            <div class="about_container">
                <div id="about_me" class="w-full">
                    <div class="page_break w-full table-row">
                        <div class="left-side">
                            <p class="font-bold">{{ __('resume.list.about') }}</p>
                        </div>
                        <div class="page_break w-full right-side about_text">
                            <div class="splitter"></div>
                            <p class="">{!! $data['about'] !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($testResult) && !empty($testResult) && isset($testResult[0]) && !empty($testResult[0]['result']))
        <table id="languages" class="w-full relative">
            @if(count($testResult[0]['result']) > 1)
                <div class="timeline-line"></div>
            @endif
            <tr class="w-full table-row">
                <td class="left-side">
                    <p class="font-bold">{{ __('resume.list.result_of_tests') }}</p>
                </td>
                <td class="w-full right-side">
                    <div class="splitter"></div>
                </td>
            </tr>
            @foreach($testResult[0]['result'] as $test)
                @if($test['quizGroup'] !== 'bookmaker')
                    <tr class="w-full table-row timeline">
                        <td class="left-side">
                            @if(count($testResult[0]['result']) > 1)
                                <span class="tl-fixer"></span>
                            @endif
                            <table id="experience-timeline" class="w-full table-space-none">
                                <tr class="w-full">
                                    @if(count($testResult[0]['result']) > 1)
                                        <td>
                                            <div class="timeline-dot"></div>
                                        </td>
                                    @endif
                                    <td>
                                        <p class="font-bold" style="font-size: 12px">
                                            {{
                                               strip_tags($test['title'])
                                            }}
                                            &mdash;
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="w-full right-side">
                            <p class="text-sm mb-4">{{ str_replace(['&nbsp;', '&amp;'], [' ', '&'], strip_tags($test['result'])) }}</p>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
        @endif
    </main>
@endsection
