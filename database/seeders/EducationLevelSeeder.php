<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educationLevels = [
            
                [
                  "name"=> 'secondary',
                  "text"=> [ "en"=> 'Secondary', "ru"=> 'Среднее образование', "uz"=> 'O‘rta ta`lim' ]
                ],
                [
                  "name"=> 'incomplete_higher',
                  "text"=> [
                    "en"=> 'Incomplete higher',
                    "ru"=> 'Неоконченное  высшее образование',
                    "uz"=> "Tugallanmagan oliy ta'lim"
                   ]
                ],

                [
                  "name"=> 'higher',
                  "text"=> [ "en"=> 'Higher', "ru"=> 'Высшее образование', "uz"=> "Oliy ta'lim" ]
                ] ,

                [
                  "name"=> 'master',
                  "text"=> [ "en"=> 'Master', "ru"=> 'Магистратура', "uz"=> 'Magistratura' ]
                ],
                [
                  "name"=> 'phd',
                  "text"=> [ "en"=> 'PhD', "ru"=> 'Докторская степень', "uz"=> 'Doktorlik' ]
                ]
          ];

        foreach($educationLevels as  $educationLevel){
            EducationLevel::query()->create($educationLevel);
        }
    }
}
