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
        $spheres = [
            ['it', 'marketing', 'education'],
            ['marketing', 'it'],
            ['it'],
        ];

        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 9),
            'avatar' => $this->faker->unique()->imageUrl(450, 450),
            'name' => $this->faker->name(),
            'surname' => $this->faker->name(),
            'spheres' => $spheres[$this->faker->numberBetween(0, 2)],
            'specialization' => $this->faker->jobTitle(),
            'birthday' => $this->faker->dateTime(),
            'address' => $this->faker->streetAddress(),
            'active' => $this->faker->boolean(),
        ];
    }
}
