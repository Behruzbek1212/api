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
        $status = ['approved', 'rejected', 'moderating'];
        $work_type = ['fulltime', 'remote', 'hybrid', 'partial'];

        return [
            'customer_id' => $this->faker->numberBetween(1, 8),
            'title' => $this->faker->jobTitle(),
            'type'=> $this->faker->jobTitle(),
            'salary' => [
                'amount' => $this->faker->randomNumber(),
                'currency' => $currency[$rand_int],
                'agreement' => $this->faker->boolean
            ],
            'about' => $this->faker->randomHtml,
            'work_type' => $work_type[$rand_int],
            'location_id' => $this->faker->numberBetween(101, 112),
            'slug' => $this->faker->unique()->slug(),
            'status' => $status[$rand_int]
        ];
    }
}
