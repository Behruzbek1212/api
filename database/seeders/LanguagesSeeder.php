<?php

namespace Database\Seeders;

use App\Models\Languages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
              "name"=> 'ar',
              "text"=> [ "en"=> 'Arabic', "ru"=> 'Арабский', "uz"=> 'Arab tili' ]
            ],
            [
              "name"=> 'en',
              "text"=> [ "en"=> 'English', "ru"=> 'English', "uz"=> 'Ingliz tili' ]
            ],
            [
              "name"=> 'ru',
              "text"=> [ "en"=> 'Russian', "ru"=> 'Русский', "uz"=> 'Rus tili' ]
            ],
            [
              "name"=> 'tr',
              "text"=> [ "en"=>'Turkish', "ru"=> 'Турецкий', "uz"=>'Turk tili' ]
            ],
            [
              "name"=> 'es',
              "text"=> [ "en"=>'Spanish', "ru"=> 'Испанский', "uz"=>'Ispan tili' ]
            ],
            [
              "name"=> 'it',
              "text"=> [ "en"=>'Italian', "ru"=> 'Итальянский', "uz"=>'Italyan tili' ]
            ],
            [
              "name"=> 'de',
              "text"=> [ "en"=>'German', "ru"=> 'Немецкий', "uz"=>'Nemis tili' ]
            ],
            [
              "name"=> 'fr',
              "text"=> [ "en"=>'French', "ru"=> 'Французский', "uz"=>'Frans"uz" tili' ]
            ],
            [
              "name"=> 'kk',
              "text"=> [ "en"=>'Kazakh', "ru"=> 'Казахский', "uz"=>'Qozoq tili' ]
            ],
            [
              "name"=> 'ky',
              "text"=> [ "en"=>'Kyrgyz', "ru"=> 'Киргизский', "uz"=>'Qirg`iz tili' ]
            ],
            [
              "name"=> 'zh',
              "text"=> [ "en"=>'Chinese', "ru"=> 'Китайский', "uz"=>'Xitoy tili' ]
            ],
            [
              "name"=> 'ko',
              "text"=> [ "en"=>'Korean', "ru"=> 'Корейский', "uz"=>'Koreys tili' ]
            ],
            [
              "name"=> 'ya',
              "text"=> [ "en"=>'Japanese', "ru"=> 'Японский', "uz"=>'Yapon tili' ]
            ],
            [
              "name"=> 'la',
              "text"=> [ "en"=>'Latin', "ru"=> 'Латинский', "uz"=>'Lotin tili' ]
            ],
            [
              "name"=> 'tg',
              "text"=> [ "en"=>'Tajik', "ru"=> 'Таджикский', "uz"=>'Tojik tili' ]
            ],
            [
              "name"=> 'uz',
              "text"=> [ "en"=>"O'zbek", "ru"=> 'Узбекский', "uz"=>'O`zbek tili' ]
            ]
        ];

        foreach($languages as $language){
            Languages::query()->create($language);
        }
          
    }
}
