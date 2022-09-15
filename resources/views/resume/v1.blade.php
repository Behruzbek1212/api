@extends('app')

@php
    /** @var \App\Models\User $user */

    $faker = \Faker\Factory::create();

    $avatar = explode('/', $user->candidate->avatar);
    $avatar = public_path('uploads/image/avatars/') . array_pop($avatar);

    if (! file_exists($avatar))
        throw new \JsonException('File does not exist');
@endphp

@section('title', $user->name)

@section('style')
    <style type="text/css">
        @page {
            margin: 40px;
            font-family: sans-serif;
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
                @if(! str_contains($avatar, 'default.webp'))
                    <td style="width: 135px; padding-right: 20px">
                        <img
                            src="{{ $avatar }}"
                            alt="{{ $user->name }}"
                            class="profile-avatar"
                            width="100%"
                        />
                    </td>
                @endif
                <td align="left" class="space-1">
                    <h1>{{ $user->name }}</h1>
                    <p>{{ $user->candidate->specialization }}</p>
                    <p style="line-height: 20px">
                        {{ $user->candidate->address }} <br />
                        {{ $user->email }} <br />
                        {{ $user->phone }} <br />
                    </p>
                </td>
            </tr>
        </table>

        <div class="splitter"></div>

        <table class="w-full">
            <tr class="w-full">
                <td style="max-width: 130px; width: 130px; vertical-align: top">
                    <h2 class="text-blue text-bold">
                        About
                    </h2>
                </td>
                <td class="w-full">
                    <p style="font-size: 14px; line-height: 19px">
                        {{ $faker->paragraph('10') }}
                    </p>
                </td>
            </tr>
        </table>

        <div class="splitter"></div>

        <table class="w-full">
            <tr class="w-full">
                <td style="max-width: 130px; width: 130px">
                    <h2 class="text-blue text-bold">
                        Education
                    </h2>
                </td>
            </tr>
            <tr class="w-full">
                <td style="width: 130px; vertical-align: top; padding-top: 15px">
                    <p style="font-size: 11px; margin-top: 4px">Nov 2005 — Sep 2010</p>
                </td>
                <td class="w-full space-1" style="padding-top: 15px">
                    <h3>Los Angeles University</h3>
                    <p style="font-size: 14px">
                        Bachelor of Fine Arts in Graphic Design, GPA: 3.4/4.0
                    </p>
                </td>
            </tr>
            <tr class="w-full">
                <td style="width: 130px; vertical-align: top; padding-top: 15px">
                    <p style="font-size: 11px; margin-top: 4px">Aug 2010 — Sep 2012</p>
                </td>
                <td class="w-full space-1" style="padding-top: 15px">
                    <h3>New York University</h3>
                    <p style="font-size: 14px">Master of Graphic Design, GPA: 3.8/4.0</p>
                </td>
            </tr>
        </table>

        <div class="splitter"></div>

        <table class="w-full">
            <tr class="w-full">
                <td style="max-width: 130px; width: 130px">
                    <h2 class="text-blue text-bold">
                        Employment
                    </h2>
                </td>
            </tr>
            <tr class="w-full">
                <td style="width: 130px; vertical-align: top; padding-top: 15px">
                    <p style="font-size: 11px; margin-top: 4px">Oct 2012 — Sep 2015</p>
                </td>
                <td class="w-full space-1" style="padding-top: 15px">
                    <h3>UI Designer at Market Studios</h3>
                    <p style="font-size: 14px">
                        Successfully translated subject into concrete design for newsletters,
                        promotional materials and sales collateral. Created design graphics for
                        marketing and sales presentations, training videos and corporate websites.
                    </p>
                </td>
            </tr>
            <tr class="w-full">
                <td style="width: 130px; vertical-align: top; padding-top: 15px">
                    <p style="font-size: 11px; margin-top: 4px">Oct 2015 — Jan 2018</p>
                </td>
                <td class="w-full space-1" style="padding-top: 15px">
                    <h3>Graphic Designer at FireWeb</h3>
                    <p style="font-size: 14px; line-height: 19px">
                        Created new design themes for marketing and collateral materials.
                        Collaborated with creative team to design and produce computer-generated
                        artwork for marketing and promotional materials.
                    </p>
                </td>
            </tr>
        </table>

        <div class="splitter"></div>

        <table class="w-full">
            <tr class="w-full">
                <td style="max-width: 130px; width: 130px; vertical-align: top">
                    <h2 class="text-blue text-bold">
                        Skills
                    </h2>
                </td>
                <td class="w-full">
                    <table class="w-full" style="padding-top: 7px">
                        <tr class="w-full">
                            <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px">
                                <h4 align="left" style="display: inline-block; width: 60%">Figma</h4>
                                <p align="right" style="display: inline-block; width: 20%">Expert</p>
                            </td>
                            <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px">
                                <h4 align="left" style="display: inline-block; width: 60%">HTML/CSS</h4>
                                <p align="right" style="display: inline-block; width: 20%">Expert</p>
                            </td>
                            <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px">
                                <h4 align="left" style="display: inline-block; width: 60%">Sketch App</h4>
                                <p align="right" style="display: inline-block; width: 20%">Expert</p>
                            </td>
                            <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px">
                                <h4 align="left" style="display: inline-block; width: 60%">Premiere Pro</h4>
                                <p align="right" style="display: inline-block; width: 20%">Expert</p>
                            </td>
                            <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px">
                                <h4 align="left" style="display: inline-block; width: 60%">Adobe Photoshop</h4>
                                <p align="right" style="display: inline-block; width: 20%">Expert</p>
                            </td>
                            <td style="display: inline-block; min-width: 280px; max-width: 280px; padding-bottom: 10px">
                                <h4 align="left" style="display: inline-block; width: 60%">After Effects</h4>
                                <p align="right" style="display: inline-block; width: 20%">Expert</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </main>
@endsection
