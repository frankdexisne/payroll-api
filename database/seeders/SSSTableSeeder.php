<?php

namespace Database\Seeders;

use App\Models\Sss;
use Illuminate\Database\Seeder;

class SSSTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *     * @return void
     */
    public function run()
    {
        $startRange = 1000;
        $endRange = 3249.99;
        $ee = 135;
        $er = 255;

        $data = [
            [
                'start_range' => $startRange,
                'end_range' => $endRange,
                'ee'  => $ee,
                'er' => $er
            ]
        ];

        while ($startRange <= 24250) {
            $startRange = $endRange + 0.01;
            $endRange = $startRange + 499.99;
            $ee = $ee + 22.5;
            $er = $er + 42.5;
            $ee = $ee >= 900 ? 900 : $ee;
            $er = $er >= 1700 ? 1700 : $er;
            array_push($data, [
                'start_range' => $startRange,
                'end_range' => $endRange,
                'ee'  => $ee,
                'er' => $er
            ]);
        }
        Sss::upsert($data, ['start_range', 'end_range'], ['ee', 'er']);
    }
}
