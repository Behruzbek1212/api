@extends('app')

@section('header')
    <meta charset="utf-8">
@endsection

@php
    $jayParsedAry = [
        [
            'label' => 'Kompyuter savodxonligini',
            'value' => 'computer',
        ],
        [
            'label' => 'IQ darajasini aniqlash',
            'value' => 'iq',
        ],
        [
            'label' => 'Qimor o`yinlari',
            'value' => 'bookmaker',
        ],
        [
            'label' => 'Temperamentni aniqlash',
            'value' => 'temperament',
        ],
        [
            'label' => 'Liderlik',
            'value' => 'leader',
        ],
        [
            'label' => 'Mas`uliyat',
            'value' => 'responsibility',
        ],
        [
            'label' => 'Sodiqlikni aniqlash',
            'value' => 'loyalty',
        ],
        [
            'label' => 'Ingliz tili',
            'value' => 'english',
        ],
        [
            'label' => 'Rus tili',
            'value' => 'russian',
        ],
    ];
    function getTestResult($tests, $testType) {
    $solvedTest = null;
    
    foreach ($tests as $test) {
        
        if ($test['quizGroup'] === $testType) {
            $solvedTest = $test;
            break;
        }
    }

    if ($solvedTest) {
     
        $testResult = $solvedTest['resultCount'] . '/' . $solvedTest['maxBall'];
        $percentage = $solvedTest['efficiensy'];

        return [
            'testResult' => $testResult,
            'percentage' => $percentage,
        ];
    } else {
        return [];
    }
    }
    
    function getAveragePercentage($tests) {
    if ($tests) {
        $totalEfficiency = array_reduce($tests, function ($acc, $test) {
            return $acc + $test['efficiensy'];
        }, 0);

        $averagePercentage = round($totalEfficiency / count($tests));
        return $averagePercentage;
    } else {
        return '-';
    }
    }

@endphp

@section('style')
    <style id="fonts" type="text/css">
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src:'/var/www/jobo.uz/_api/storage/fonts/Roboto-Regular.ttf' format("truetype");
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 600;
            src: '/var/www/jobo.uz/_api/storage/fonts/Roboto-Regular.ttf' format("truetype");
        }

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 700;
            src: '/var/www/jobo.uz/_api/storage/fonts/Roboto-Regular.ttf' format("truetype");
        }
    </style>


    <style type="text/css">
        * {
            padding: 0;
            margin: 0;
            font-family: Roboto, sans-serif;
        }

        #wrapper {
            /* max-width: 900px; */
            width: 100%;
            padding: 10px;
        }

        table {
            background-color: #f4f6f9;
        }

        .table-head {
            position: relative;
            color: #3d4b6c;
            padding: 20px 15px;
            font-weight: 500;
            font-size: 12px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .table-items {
            white-space: nowrap;
            text-align: center;
            position: relative;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 20px 10px;
            font-size: 15px;
            margin: 2px 0;
            color: #3d4b6c;
            font-weight: 500;
        }

        .table-head,
        .table-items {
            border-top: 2px solid #fff;
            position: relative;
        }

        .table-head::before,
        .table-items::before {
            content: "";
            position: absolute;
            right: 0;
            /* top: 50%;
      transform: translateY(-50%); */
            background: #c1c2c6;
            height: 30px;
            width: 1px;
        }

        .table-head:last-child::before,
        .table-items:last-child::before {
            opacity: 0;
        }
    </style>
@endsection

@section('content')
    <section id="wrapper">

        <table>
            <thead>
                <tr>
                    <th class="table-head">N</th>
                    <th class="table-head fixed-column">Ism Familiya</th>
                    <th class="table-head">Lavozim</th>
                    @foreach ( $jayParsedAry as $val )
                    <th class="table-head">{{$val['label']}}</th>
                    @endforeach
                   
                    <th class="table-head">O'rtacha foiz</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        <td class="table-items">{{$key +1}}</td>
                        <td class="table-items underline !text-blue cursor-pointer fixed-column">
                            {{ $value['candidate'] == null ? '' : $value['candidate']['name'] }}
                        </td>
                       
                        <td class="table-items">{{ $value['candidate'] == null ?  '' : $value['candidate']['specialization']}}</td>
                        @foreach ( $jayParsedAry as $val )
                       @php
                           $data =  getTestResult($value['result'], $val['value']);
                           $result = count($value['result']);
                            $persents =  getAveragePercentage($value['result']);
                       @endphp
                        <td class="table-items">
                            @if ($data !== [])
                                   <span>{{  $data['testResult'] }} | </span>
                                   <span>{{$data['percentage']}}%</span>
                            @else
                                
                            @endif
                            {{-- <span>{{  $testResult }} | </span>
                            <span>40%</span> --}}
                        </td>
                        @endforeach
                       
                        
                        <td class="table-items">
                            @if ($persents  !== '-')
                            <span>{{$result}}/{{count($jayParsedAry)}} | </span>
                            <span>{{$persents}}%</span>
                            @else
                                
                            @endif
                           
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </section>
@endsection
