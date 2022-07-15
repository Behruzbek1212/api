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
        return [
            'customer_id' => $this->faker->unique()->numberBetween(),
            'title' => $this->faker->jobTitle(),
            'salary' => $this->faker->randomNumber(),
            'type'=> $this->faker->jobTitle(),
            'requirements' => ['Ok', 'Low', 'Message'],
            'tasks' => ['Ok', 'Low', 'Message'],
            'advantages' => ['Ok', 'Low', 'Message'],
            'location_id' => $this->faker->numberBetween(1, 10)
        ];
    }
}
