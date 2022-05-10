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
                'gender_id' => null
            ],
            [
                'name' => 'Paternity Leave',
                'no_of_days' => 0,
                'gender_id' => 1
            ],
            [
                'name' => 'Maternity Leave',
                'no_of_days' => 0,
                'gender_id' => 2
            ],
            [
                'name' => 'Sick Leave',
                'no_of_days' => 0,
                'gender_id' => null
            ],
            [
                'name' => 'Unpaid Leave',
                'no_of_days' => 0,
                'gender_id' => null
            ],
        ];
        Leave::upsert($data, ['name'], ['name']);
    }
}
