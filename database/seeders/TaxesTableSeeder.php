<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxesTableSeeder extends Seeder
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
                'annual_income_bracket_lower_limit' => 0,
                'annual_income_bracket_upper_limit' => 250000,
                'tax_rate_lower_limit' => 0,
                'tax_rate_excess_over_lower_limit' => 0
            ],
            [
                'annual_income_bracket_lower_limit' => 250000,
                'annual_income_bracket_upper_limit' => 400000,
                'tax_rate_lower_limit' => 0,
                'tax_rate_excess_over_lower_limit' => 0.2
            ],
            [
                'annual_income_bracket_lower_limit' => 400000,
                'annual_income_bracket_upper_limit' => 800000,
                'tax_rate_lower_limit' => 30000,
                'tax_rate_excess_over_lower_limit' => 0.25
            ],
            [
                'annual_income_bracket_lower_limit' => 800000,
                'annual_income_bracket_upper_limit' => 2000000,
                'tax_rate_lower_limit' => 130000,
                'tax_rate_excess_over_lower_limit' => 0.3
            ],
            [
                'annual_income_bracket_lower_limit' => 2000000,
                'annual_income_bracket_upper_limit' => 8000000,
                'tax_rate_lower_limit' => 490000,
                'tax_rate_excess_over_lower_limit' => 0.32
            ]
        ];

        Tax::upsert(
            $data,
            ['annual_income_bracket_lower_limit', 'annual_income_bracket_lower_limit'],
            ['tax_rate_lower_limit', 'tax_rate_excess_over_lower_limit']
        );
    }
}
