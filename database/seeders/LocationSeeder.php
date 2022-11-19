<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run()
    {
        foreach ( config('locations') as $data ) {
            DB::table('locations')->insert([
                'id' => $data['id'],
                'title' => $data['addressTitle'],
                'parent_id' => @$data['parentId']
            ]);
        }
    }
}
