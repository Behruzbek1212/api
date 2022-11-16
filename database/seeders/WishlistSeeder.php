<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    protected int $count = 0;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        do {
            DB::table('wishlists')->insert([
                'user_id' => fake()->unique()->numberBetween(1, 40),
                'job_slug' => fake()->unique()->slug()
            ]);

            $this->count++;
        } while ($this->count < 20);
    }
}
