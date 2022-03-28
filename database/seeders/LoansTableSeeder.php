<?php

namespace Database\Seeders;

use App\Models\Loan;
use Illuminate\Database\Seeder;

class LoansTableSeeder extends Seeder
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
                'name' => 'Pagibig Loan'
            ],
            [
                'name' => 'SSS Loan'
            ]
        ];
        Loan::upsert($data, ['name'], ['name']);
    }
}
