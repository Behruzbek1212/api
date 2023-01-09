<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guide>
 */
class GuideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $rand_int = $this->faker->numberBetween(0, 2);

        $images = [
            'https://static.jobo.uz/img/1.png',
            'https://static.jobo.uz/img/2.png',
            'https://static.jobo.uz/img/3.png',
        ];

        $backgrounds = [
            '#0079FE',
            '#FFA800',
            '#FF6985'
        ];

        $buttons = [
            '#FFA800',
            '#0079FE',
            '#FFA800'
        ];

        $roles = [
            'customer',
            'candidate',
            'all'
        ];

        return [
            'title_uz' => $this->faker->text(50),
            'title_ru' => $this->faker->text(50),
            'title_en' => $this->faker->text(50),
            'background' => [
                'color' => $backgrounds[$rand_int],
                'image' => $images[$rand_int]
            ],
            'button' => [
                'text' => 'more',
                'color' => '#FFFFFF',
                'background' => $buttons[$rand_int]
            ],
            'content_uz' => $this->faker->realTextBetween(700, 1200),
            'content_ru' => $this->faker->realTextBetween(700, 1200),
            'content_en' => $this->faker->realTextBetween(700, 1200),
            'role' => $roles[$rand_int],
            'slug' => $this->faker->unique()->slug()
        ];
    }
}
