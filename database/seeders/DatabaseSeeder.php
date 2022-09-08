<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Customer;
use App\Models\Guide;
use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(16)->create();
        // Candidate::factory(8)->create();
        // Customer::factory(8)->create();
        Guide::factory(45)->create();
        Job::factory(45)->create();
        // Resume::factory(8)->create();

        $this->call([
            WishlistSeeder::class
        ]);
    }
}
