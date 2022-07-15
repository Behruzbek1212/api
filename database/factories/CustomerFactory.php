<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 40),
            'name' => $this->faker->company(),
            'balance' => $this->faker->randomNumber(),
            'owned_date' => $this->faker->dateTime(),
            'address' => $this->faker->address(),
            'active' => $this->faker->boolean(),
        ];
    }
}
