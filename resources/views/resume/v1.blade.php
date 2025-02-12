@extends('app')

@php
    /**
     * @var \App\Models\Candidate|\Illuminate\Contracts\Auth\Authenticatable $candidate
     * @var array $data
     */

    $faker = \Faker\Factory::create();

    $avatar = str_replace("https://static.jobo.uz/", "", $candidate->avatar);
    $avatar = public_path($avatar);

    if (! file_exists($avatar))
        throw new \ErrorException('File does not exist');
@endphp

@section('title', $candidate->name)

@section('style')
    <style type="text/css">
        @page {
            margin: 40px;
            font-family: "DejaVu Sans", sans-serif;
        }

        footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
        }

        h1, h2, h3, h4, h5, h6, p {
            padding: 0;
            margin: 0;
            color: #212121;
        }

        p {
            color: #21212199;
        }

        p * {
            font-size: 16px;
        }

        .text-blue {
            color: #0079FE;
        }

        .w-full {
            width: 100%;
        }

        .profile-avatar {
            border-radius: 12px;
        }

        .space-1 > * ~ * {
            margin-top: 7px;
        }

        .splitter {
            width: 100%;
            height: 1px;
            margin-top: 15px;
            margin-bottom: 15px;
            background-color: #0079FE1A;
        }

        /* Page number */
        .page_number { color: #0079FE }
        .page_number::after {
            content: counter(page);
        }
    </style>
@endsection

@section('content')
    <footer>
        <table class="w-full">
            <tr class="w-full">
                <td><img src="{{ public_path('assets/logo.png') }}" alt="Logo" /></td>
                <td align="right"><span class="page_number">{{ __('Page') }}: #</span></td>
            </tr>
        </table>
    </footer>

    <main>
        <table class="w-full">
            <tr class="w-full">
                @if(! str_contains($avatar, 'default.webp') && ! str_contains($avatar, 'avatar.png'))
                    <td style="width: 135px; padding-right: 20px">
                        <img
                            src="{{ $avatar }}"
                            alt="{{ $candidate->name }}"
                            class="profile-avatar"
                            width="100%"
                        />
                    </td>
                @endif
                <td align="left" class="space-1">
                    <h1>{{ $candidate->name }}</h1>
                    <p>{{ $candidate->specialization }}</p>
                    <p style="line-height: 20px">
                        {{ $candidate->location }} <br />
                        {{ $candidate->email }} <br />
                        {{ $candidate->phone }} <br />
                    </p>
                </td>
            </tr>
        </table>

        <div class="splitter"></div>

        <table class="w-full">
            <tr class="w-full">
                <td style="max-width: 130px; width: 130px; vertical-align: top">
                    <h2 class="text-blue text-bold">
                        {{ __('About') }}
                    </h2>
                </td>
                <td class="w-full">
                    <p style="font-size: 14px; line-height: 19px">
                        {!! $data['about'] !!}
                    </p>
                </td>
            </tr>
        </table>

        <div class="splitter"></div>

        <table class="w-full">
            <tr class="w-full">
                <td style="max-width: 130px; width: 130px">
                    <h2 class="text-blue text-bold">
                        {{ __('Education') }}
                    </h2>
                </td>
            </tr>
            @foreach($data['education'] as $education)
                <tr class="w-full">
                    <td style="width: 130px; vertical-align: top; padding-top: 15px">
                        <p style="font-size: 11px; margin-top: 4px">
                            {{ __('month.' . $education['date']['start']['month']) }}
                            {{ $education['date']['start']['year'] }}
                            -
                            {{ __('month.' . $education['date']['end']['month']) }}
                            {{ $education['date']['end']['year'] }}
                        </p>
                    </td>
                    <td class="w-full space-1" style="padding-top: 15px">
                        <h3>{{ $education['school'] }} - {{ $education['degree'] }}</h3>
                        <p style="font-size: 14px">
                            {!! $education['description'] !!}
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="splitter"></div>

        <table class="w-full">
            <tr class="w-full">
                <td style="max-width: 130px; width: 130px">
                    <h2 class="text-blue text-bold">
                        {{ __('Employment') }}
                    </h2>
                </td>
            </tr>
            @foreach($data['employment'] as $employment)
                <tr class="w-full">
                    <td style="width: 130px; vertical-align: top; padding-top: 15px">
                        <p style="font-size: 11px; margin-top: 4px">
                            {{ __('month.' . $employment['date']['start']['month']) }}
                            {{ $employment['date']['start']['year'] }}
                            -
                            {{ __('month.' . $employment['date']['end']['month']) }}
                            {{ $employment['date']['end']['year'] }}
                        </p>
                    </td>
                    <td class="w-full space-1" style="padding-top: 15px">
                        <h3>{{ $employment['title'] }} at {{ $employment['employer'] }}</h3>
                        <p style="font-size: 14px">
                            {!! $employment['description'] !!}
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="splitter"></div>

        {{-- <table class="w-full"> --}}
        {{--     <tr class="w-full"> --}}
        {{--         <td style="max-width: 130px; width: 130px; vertical-align: top"> --}}
        {{--             <h2 class="text-blue text-bold"> --}}
        {{--                 Skills --}}
        {{--             </h2> --}}
        {{--         </td> --}}
        {{--         <td class="w-full"> --}}
        {{--             <table class="w-full" style="padding-top: 7px"> --}}
        {{--                 <tr class="w-full"> --}}
        {{--                     <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px"> --}}
        {{--                         <h4 align="left" style="display: inline-block; width: 60%">Figma</h4> --}}
        {{--                         <p align="right" style="display: inline-block; width: 20%">Expert</p> --}}
        {{--                     </td> --}}
        {{--                     <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px"> --}}
        {{--                         <h4 align="left" style="display: inline-block; width: 60%">HTML/CSS</h4> --}}
        {{--                         <p align="right" style="display: inline-block; width: 20%">Expert</p> --}}
        {{--                     </td> --}}
        {{--                     <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px"> --}}
        {{--                         <h4 align="left" style="display: inline-block; width: 60%">Sketch App</h4> --}}
        {{--                         <p align="right" style="display: inline-block; width: 20%">Expert</p> --}}
        {{--                     </td> --}}
        {{--                     <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px"> --}}
        {{--                         <h4 align="left" style="display: inline-block; width: 60%">Premiere Pro</h4> --}}
        {{--                         <p align="right" style="display: inline-block; width: 20%">Expert</p> --}}
        {{--                     </td> --}}
        {{--                     <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px"> --}}
        {{--                         <h4 align="left" style="display: inline-block; width: 60%">Adobe Photoshop</h4> --}}
        {{--                         <p align="right" style="display: inline-block; width: 20%">Expert</p> --}}
        {{--                     </td> --}}
        {{--                     <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px"> --}}
        {{--                         <h4 align="left" style="display: inline-block; width: 60%">After Effects</h4> --}}
        {{--                         <p align="right" style="display: inline-block; width: 20%">Expert</p> --}}
        {{--                     </td> --}}
        {{--                 </tr> --}}
        {{--             </table> --}}
        {{--         </td> --}}
        {{--     </tr> --}}
        {{-- </table> --}}
    </main>
@endsection
