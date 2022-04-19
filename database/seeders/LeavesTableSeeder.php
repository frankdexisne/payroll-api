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
                'no_of_days' => 0
            ],
            [
                'name' => 'Paternity Leave',
                'no_of_days' => 0
            ],
            [
                'name' => 'Maternity Leave',
                'no_of_days' => 0
            ],
            [
                'name' => 'Sick Leave',
                'no_of_days' => 0
            ],
            [
                'name' => 'Unpaid Leave',
                'no_of_days' => 0
            ],
        ];
        Leave::upsert($data, ['name'], ['name']);
    }
}
