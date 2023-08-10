<?php

namespace Database\Seeders;

use App\Models\CustomerStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusColumns = [
          
                [
                    "name" => [
                        "ru" => "Тестируемые", 
                        "en" => "Tested", 
                        "uz" => "Test topshirganlar" 
                    ], 
                    "required" => true, 
                    "status" => "test" 
                   ], 
                [
                    "name" => [
                        "ru" => "Обзор", 
                        "en" => "Review", 
                        "uz" => "ko'rib chiqish" 
                    ], 
                    "required" => true, 
                    "status" => "review" 
                ], 
                [
                    "name" => [
                        "ru" => "Беседа", 
                        "en" => "Conversation", 
                        "uz" => "Suhbat" 
                        ], 
                    "required" => true, 
                    "status" => "approved" 
                ], 
                [
                    "name" => [
                        "ru" => "Тестовые задания", 
                        "en" => "Test tasks", 
                        "uz" => "Test topshiriqlari" 
                        ], 
                    "required" => true, 
                    "status" => "test-task" 
                ], 
                [
                    "name" => [
                        "ru" => "Испытательный срок", 
                        "en" => "Trial period", 
                        "uz" => "Sinov muddati" 
                        ], 
                    "required" => true, 
                    "status" => "trial" 
                ], 
                [
                    "name" => [
                            "ru" => "Набор персонала", 
                            "en" => "Recruitment", 
                            "uz" => "Ishga qabul qilinganlar" 
                    ], 
                    "required" => true, 
                    "status" => "recruiting" 
                ], 
                [
                    
                    "name" => [
                            "ru" => "Отклоненный", 
                            "en" => "Rejected", 
                            "uz" => "Rad etilgan" 
                    ], 
                    "required" => true, 
                    "status" => "rejected" 
                ]   
        ];

        foreach($statusColumns as $statusColumn){
             $status = CustomerStatus::query()->create($statusColumn);
        }

        

    }
}
