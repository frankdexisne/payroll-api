<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
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
                'name' => 'IT Department'
            ],
            [
                'name' => 'Accounting'
            ]
        ];
        Department::upsert($data, ['name'], ['name']);
    }
}
