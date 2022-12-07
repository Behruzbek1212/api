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
            'user_id' => $this->faker->unique()->numberBetween(1, 20),
            'avatar' => $this->faker->unique()->imageUrl(450, 450),
            'name' => $this->faker->company(),
            'balance' => $this->faker->randomNumber(),
            'owned_date' => $this->faker->dateTime(),
            'location' => $this->faker->city(),
            'address' => $this->faker->address(),
            'active' => $this->faker->boolean(),
        ];
    }
}
