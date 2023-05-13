<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function all()
    {
        $location = Location::whereNotNull('name')->get();

        return response()->json([
            'status' => true,
            'data' => $location
        ]);
    }

    public function get(Request $request)
    {
        $user = Location::where('parent_id', '=', request('id'))->get();

        return response()->json([
            'status' => true,
            'data' => $user,
        ]);
    }

    public function region(Request $request)
    {
        $user = Location::where('id', '>', 100000)->get();
        return response()->json([
            'status' => true,
            'data' => $user,
        ]);
    }


    public function add(Request $request)
    {
        $uz_districts = json_decode(file_get_contents(storage_path('app/public/uz.json')), true);
        $ru_districts = json_decode(file_get_contents(storage_path('app/public/ru.json')), true);
        $en_districts = json_decode(file_get_contents(storage_path('app/public/en.json')), true);

        $districts = [];
        foreach ($uz_districts['addresses'] as $key => $uz_district) {
            $districts[$key]['uz'] = $uz_district;
        }

        foreach ($ru_districts['addresses'] as $key => $ru_district) {
            $districts[$key]['ru'] = $ru_district;
        }

        foreach ($en_districts['addresses'] as $key => $en_district) {
            $districts[$key]['en'] = $en_district;
        }

        foreach ($districts as $key => $district) {
            $key = 'addresses.' . $key;
            $locations = Location::updateOrCreate([
                'title' => $key,
            ], [
                'name' => $district
            ]);
        }

    }
}
