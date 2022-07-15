<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Customer;
use App\Models\Guide;
use App\Models\Job;
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
        User::factory(15)->create();
        Candidate::factory(15)->create();
        Customer::factory(15)->create();
        Guide::factory(45)->create();
        Job::factory(45)->create();

        $this->call([
            WishlistSeeder::class
        ]);
    }
}
