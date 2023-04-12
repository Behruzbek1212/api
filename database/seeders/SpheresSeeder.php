<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class SpheresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $spheres = [
            [
                "name" => "design",
                "text" => [
                    "uz" => "Dizayn",
                    "en" => "Design",
                    "ru" => "Дизайн"
                ]
            ],

            [
                "name" => "it",
                "text" => [
                    "uz" => "IT, Telekom",
                    "en" => "it",
                    "ru" => "IT, телеком"
                ]
            ],

            [
                "name" => "selling",
                "text" => [
                    "uz" => "Savdo-sotiq",
                    "en" => "selling",
                    "ru" => "Продажа"
                ]
            ],

            [
                "name" => "management",
                "text" => [
                    "uz" => "Menejment",
                    "en" => "management",
                    "ru" => "Менеджмент"
                ]
            ],

            [
                "name" => "marketing",
                "text" => [
                    "uz" => "Marketing",
                    "en" => "marketing",
                    "ru" => "Маркетинг"
                ]
            ],

            [
                "name" => "tourism",
                "text" => [
                    "uz" => "Sayyohlik",
                    "en" => "tourism",
                    "ru" => "Туризм"
                ]
            ],

            [
                "name" => "medicine",
                "text" => [
                    "uz" => "Tibbiyot",
                    "en" => "medicine",
                    "ru" => "Медицина"
                ]
            ],

            [
                "name" => "education",
                "text" => [
                    "uz" => "Ta'lim",
                    "en" => "education",
                    "ru" => "Образование"
                ]
            ],

            [
                "name" => "maintenance",
                "text" => [
                    "uz" => "Xizmat ko‘rsatish",
                    "en" => "maintenance",
                    "ru" => "Обслуживание"
                ]
            ],

            [
                "name" => "production",
                "text" => [
                    "uz" => "Ishlab chiqarish",
                    "en" => "production",
                    "ru" => "Производство"
                ]
            ],

            [
                "name" => "finance",
                "text" => [
                    "uz" => "Buxgalteriya va finans",
                    "en" => "finance",
                    "ru" => "Бухгалтерский учет и финансы"
                ]
            ],

            [
                "name" => "art",
                "text" => [
                    "uz" => "Madaniyat, san'at, ko‘ngil ochar",
                    "en" => "art",
                    "ru" => "Культура, искусство, развлечения"
                ]
            ],

            [
                "name" => "administrative",
                "text" => [
                    "uz" => "Boshqaruv xodimlari",
                    "en" => "administrative",
                    "ru" => "Административный персонал"
                ]
            ],

            [
                "name" => "automotive",
                "text" => [
                    "uz" => "Transport, Avto - biznes, servis",
                    "en" => "automotive",
                    "ru" => "Транспорт, Авто - бизнес, сервис"
                ]
            ],

            [
                "name" => "banks",
                "text" => [
                    "uz" => "Bank, investitsiyalar",
                    "en" => "banks",
                    "ru" => "Банк, инвестиции"
                ]
            ],

            [
                "name" => "construction",
                "text" => [
                    "uz" => "Qurilish, ko‘chmas mulk, rieltor",
                    "en" => "construction",
                    "ru" => "Строительство, недвижимость, риэлтор"
                ]
            ],

            [
                "name" => "consulting",
                "text" => [
                    "uz" => "Maslahat berish",
                    "en" => "consulting",
                    "ru" => "Консультирование"
                ]
            ],

            [
                "name" => "domestic",
                "text" => [
                    "uz" => "Uy xodimlari",
                    "en" => "domestic",
                    "ru" => "Домашний персонал"
                ]
            ],

            [
                "name" => "government",
                "text" => [
                    "uz" => "Davlat xizmati",
                    "en" => "government",
                    "ru" => "Государственная служба"
                ]
            ],


            [
                "name" => "hr",
                "text" => [
                    "uz" => "HR, xodimlar, treninglar",
                    "en" => "hr",
                    "ru" => "HR, кадры, тренинги"
                ]
            ],


            [
                "name" => "installation",
                "text" => [
                    "uz" => "O‘rnatish va servis",
                    "en" => "installation",
                    "ru" => "Инсталляция и сервис"
                ]
            ],


            [
                "name" => "insurance",
                "text" => [
                    "uz" => "Sug‘urta",
                    "en" => "insurance",
                    "ru" => "Страхование"
                ]
            ],


            [
                "name" => "lawyers",
                "text" => [
                    "uz" => "Huquqshunoslik",
                    "en" => "lawyers",
                    "ru" => "Юриспруденция"
                ]
            ],


            [
                "name" => "mass",
                "text" => [
                    "uz" => "OAV, Nashriyotlar",
                    "en" => "mass",
                    "ru" => "СМИ, Издательства"
                ]
            ],


            [
                "name" => "media",
                "text" => [
                    "uz" => "Media",
                    "en" => "media",
                    "ru" => "Медиа"
                ]
            ],


            [
                "name" => "agriculture",
                "text" => [
                    "uz" => "Qishloq xo‘jaligi",
                    "en" => "agriculture",
                    "ru" => "Cельское хозяйство"
                ]
            ],


            [
                "name" => "advertising",
                "text" => [
                    "uz" => "Reklama, PR",
                    "en" => "advertising",
                    "ru" => "Реклама, PR"
                ]
            ],


            [
                "name" => "procurement",
                "text" => [
                    "uz" => "Xaridlar",
                    "en" => "procurement",
                    "ru" => "Закупка"
                ]
            ],

            [
                "name" => "security",
                "text" => [
                    "uz" => "Xavfsizlik",
                    "en" => "security",
                    "ru" => "Безопасность"
                ]
            ],

            [
                "name" => "sport",
                "text" => [
                    "uz" => "Go‘zallik, fitnes, sport",
                    "en" => "sport",
                    "ru" => "Красота, Фитнес, Спорт"
                ]
            ],

            [
                "name" => "hotel",
                "text" => [
                    "uz" => "Mehmonxonalar, restoranlar",
                    "en" => "hotel",
                    "ru" => "Гостиницы, рестораны"
                ]
            ],

            [
                "name" => "pharmaceutical",
                "text" => [
                    "uz" => "Farmatsevtika",
                    "en" => "pharmaceutical",
                    "ru" => "Фармацевтика"
                ]
            ],

            [
                "name" => "science",
                "text" => [
                    "uz" => "Ilm-fan",
                    "en" => "science",
                    "ru" => "Наука"
                ]
            ],

            [
                "name" => "logistics",
                "text" => [
                    "uz" => "Logistika, ombor",
                    "en" => "logistics",
                    "ru" => "Логистика, склад"
                ]
            ],

            [
                "name" => "topmanagement",
                "text" => [
                    "uz" => "Top menejment, boshqaruv",
                    "en" => "topmanagement",
                    "ru" => "Топ менеджмент, управления"
                ]
            ],

            [
                "name" => "without",
                "text" => [
                    "uz" => "Maxsus tayyorgarliksiz, tajribasiz",
                    "en" => "without",
                    "ru" => "Работа без специальной подготовки, без опыта"
                ]
            ],

            [
                "name" => "all_spheres",
                "text" => [
                    "uz" => "Hamma kategoriyalar",
                    "en" => "all_spheres",
                    "ru" => "Все сферы"
                ]
            ],
        ];

        foreach ($spheres as $sphere) {
            Category::query()->create($sphere);
        }
    }
}
