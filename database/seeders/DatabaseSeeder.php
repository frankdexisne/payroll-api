<?php

namespace Database\Seeders;

use App\Models\Contribution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

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
        $this->call(LeavesTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(RelationshipsTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(ShiftsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SSSTableSeeder::class);
        $this->call(TaxTableSeeder::class);
    }
}
