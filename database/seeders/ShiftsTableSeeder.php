<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftsTableSeeder extends Seeder
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
                'shift' => '1',
                'start' => '08:00:00',
                'end' => '12:00:00'
            ],
            [
                'shift' => '2',
                'start' => '13:00:00',
                'end' => '17:00:00'
            ]
        ];
        Shift::upsert($data, ['shift'], ['shift']);
    }
}
