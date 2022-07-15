<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
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
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'birthday' => $this->faker->dateTime(),
            'address' => $this->faker->streetAddress(),
            'active' => $this->faker->boolean(),
        ];
    }
}
