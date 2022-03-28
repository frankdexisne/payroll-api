<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
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
                'name' => 'IT'
            ],
            [
                'name' => 'Staff'
            ]
        ];
        Position::upsert($data, ['name'], ['name']);
    }
}
