<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $rand_int = $this->faker->numberBetween(0, 2);

        $currency = ['USD', 'RUB', 'UZS'];

        $requirements = [
            ['lorem', 'ipsum', 'dolor', 'sit', 'amet'],
            ['ipsum', 'dolor', 'lorem', 'amet', 'sit'],
            ['dolor', 'ipsum', 'lorem', 'sit', 'amet'],
        ];

        $status = ['approved', 'rejected', 'moderating'];

        return [
            'customer_id' => $this->faker->numberBetween(1, 8),
            'title' => $this->faker->jobTitle(),
            'salary' => [
                'amount' => $this->faker->randomNumber(),
                'currency' => $currency[$rand_int]
            ],
            'type'=> $this->faker->jobTitle(),
            'requirements' => $requirements[$rand_int],
            'tasks' => $requirements[$rand_int],
            'advantages' => $requirements[$rand_int],
            'location_id' => $this->faker->numberBetween(1, 10),
            'slug' => $this->faker->unique()->slug(),
            'status' => $status[$rand_int]
        ];
    }
}
