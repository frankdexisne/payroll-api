<?php

namespace Database\Seeders;

use App\Models\Contribution;
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
        $this->call(ContributionsTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(GendersTableSeeder::class);
        $this->call(LoansTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(RelationshipsTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
    }
}
