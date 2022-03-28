<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
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
                'name' => 'Single'
            ],
            [
                'name' => 'Married'
            ],
            [
                'name' => 'Widow'
            ]
        ];
        Status::upsert($data, ['name'], ['name']);
    }
}
