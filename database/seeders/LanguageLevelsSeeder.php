<?php

namespace Database\Seeders;

use App\Models\LanguageLevels;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languageLevels = [
            [
              "name"=> 'a1',
              "text"=> [ "en"=>'No Proficiency', "ru"=> 'Нет квалификации', "uz"=> "Boshlang'ich" ]
            ],
            [
              "name"=> 'a2',
              "text"=> [ "en"=> 'Elementary', "ru"=> 'Элементарный', "uz"=> 'Elementary' ]
            ],
            [
              "name"=> 'b1',
              "text"=> [ "en"=> 'Limited Working', "ru"=> 'Средний', "uz"=> "O'rta" ]
              ],
            [
              "name"=> 'b2',
              "text"=> [
                "en"=> 'Professional Working',
                "ru"=> 'Выше среднего',
                "uz"=> "O'rtadan yuqori"
              ]
            ],
            [
              "name"=> 'c1',
              "text"=> [ "en"=> 'Full Professional', "ru"=> 'Продвинутый', "uz"=> 'Yuqori darajada' ]
              ],
            [
              "name"=> 'c2',
              "text"=> [ "en"=> 'Native', "ru"=> 'Профессиональный', "uz"=> 'Professional' ]
              ]
            ];

            foreach($languageLevels  as $languageLevel){
                LanguageLevels::query()->create($languageLevel);
            }
    }
}
