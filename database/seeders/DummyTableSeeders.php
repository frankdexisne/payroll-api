<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use App\Models\Employee;

class DummyTableSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeSets = Config::get('dummy');
        foreach ($employeeSets as $employeeSet) {
            $rowEmployee = Arr::except($employeeSet, ['families', 'educational_backgrounds', 'rates', 'addresses']);
            $employee = Employee::firstOrNew([
                'employee_no' => $rowEmployee['employee_no']
            ]);
            if (!$employee->exists) {
                $employee->fill(
                    Arr::except($rowEmployee, ['employee_no'])
                )
                    ->save();
            }
            $employee->rate()->create($employeeSet['rates']);
            foreach ($employeeSet['families'] as $family) {
                $employee->family()->create($family);
            }
            foreach ($employeeSet['educational_backgrounds'] as $educationalBackground) {
                $employee->educationalBackground()->create($educationalBackground);
            }
            foreach ($employeeSet['addresses'] as $address) {
                $employee->address()->create($address);
            }
        }
    }
}
