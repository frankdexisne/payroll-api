<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contribution;

class ContributionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Philhealth'
            ],
            [
                'name' => 'Pagibig'
            ],
            [
                'name' => 'Witholding Tax'
            ],
            [
                'name' => 'SSS'
            ]
        ];
        Contribution::upsert($data, ['name'], ['name']);
    }
}
