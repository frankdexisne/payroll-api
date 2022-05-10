<?php

namespace Database\Seeders;

use App\Models\Relationship;
use Illuminate\Database\Seeder;

class RelationshipsTableSeeder extends Seeder
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
                'name' => 'Father'
            ],
            [
                'name' => 'Mother'
            ],
            [
                'name' => 'Children'
            ],
            [
                'name' => 'Spouse'
            ]
        ];
        Relationship::upsert($data, ['name'], ['name']);
    }
}
