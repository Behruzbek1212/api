<?php

namespace Database\Seeders;

use App\Models\SocialStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialStatus  = [
            [
              "name"=> 'student',
              "text"=> [ 'en'=> 'Student', "ru" => 'Студент', "uz"=> 'Talaba' ]
            ],
            [
              "name"=> 'married',
              "text"=> [ 'en'=> 'Married', "ru" => 'Семейный', "uz"=> 'Oilali' ]
            ],
            [
              "name"=> 'single',
              "text"=> [ 'en'=> 'Single', "ru"  => 'Холост / не замужем', "uz"=> 'Oila qurmagan' ]
            ]
        ];

        foreach( $socialStatus as  $social){
            SocialStatus::query()->create($social);
        }
    }
}
