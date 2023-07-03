<?php

namespace Database\Seeders;

use App\Models\ResumeBall;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResumeBallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resumeBalls = [
            ['name' => 'name', 'ball' => 10],
            ['name' => 'email', 'ball' => 10],
            ['name' => 'surname', 'ball' => 10],
            ['name' => 'education_level', 'ball' => 10],
            ['name' => 'languages', 'ball' => 10],
            ['name' => 'specialization', 'ball' => 10],
            ['name' => 'birthday', 'ball' => 10],
            ['name' => 'address', 'ball' => 10],
            ['name' => 'test', 'ball' => 10],
            ['name' => 'about', 'ball' => 10],
            ['name' => 'position', 'ball' => 10],
            ['name' => 'employment', 'ball' => 10],
            ['name' => 'sphere', 'ball' => 10],
            ['name' => 'salary', 'ball' => 5],
            ['name' => 'location', 'ball' => 5],
            ['name' => 'work_type', 'ball' => 5],
            ['name' => 'computer_skills', 'ball' => 7],
            ['name' => 'additional_education', 'ball' => 7],
            ['name' => 'education', 'ball' => 10],
            ['name' => 'skills', 'ball' => 7],
            ['name' => 'links', 'ball' => 5],
            ['name' => 'availability_of_a_car', 'ball' => 5],
            ['name' => 'categories_of_driving', 'ball' => 5],
        ];

        foreach($resumeBalls as $resumeBall){
            ResumeBall::query()->create($resumeBall);
        }
    }
}
