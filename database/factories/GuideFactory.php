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
        return [
            'title' => $this->faker->text(50),
            'design' => ['background' => '#252525', 'button' => '#454545', 'image' => '/img/img1.jpg'],
            'image' => '/img/img1.jpg',
            'content' => $this->faker->realTextBetween(300, 800),
            'content_button' => '#454545',
            'role' => ['customer', 'candidate', 'all'][$this->faker->numberBetween(0, 2)],
            'slug' => $this->faker->slug()
        ];
    }
}
