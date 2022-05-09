<?php

namespace Database\Seeders;

use App\Models\Leave;
use Illuminate\Database\Seeder;

class LeavesTableSeeder extends Seeder
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
                'name' => 'Vacation Leave',
                'no_of_days' => 0,
                'for_male' => null
            ],
            [
                'name' => 'Paternity Leave',
                'no_of_days' => 0,
                'for_male' => 1
            ],
            [
                'name' => 'Maternity Leave',
                'no_of_days' => 0,
                'for_male' => 2
            ],
            [
                'name' => 'Sick Leave',
                'no_of_days' => 0,
                'for_male' => null
            ],
            [
                'name' => 'Unpaid Leave',
                'no_of_days' => 0,
                'for_male' => null
            ],
        ];
        Leave::upsert($data, ['name'], ['name']);
    }
}
