<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\Employee;
use App\Models\Payslip;
use App\Models\Attend;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeBasic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cutoff;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cutoff $cutoff)
    {
        $this->cutoff = $cutoff;
        // $this->queue = 'process_payroll';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $employeeIds = $this->cutoff->employeeIdsHasAttendance();

        $employees = Employee::whereIn('id', $employeeIds)->get();
        $attends = collect(
            Attend::whereBetween('attend_date', [$this->cutoff->from, $this->cutoff->to])
                ->get()->toArray()
        );

        $payslips = [];

        foreach ($employees as $employee) {
            $employeeTotalRenderHours = 0;
            $attendsByEmployee = $attends->where('employee_id', $employee->id);
            $attendDates = $attendsByEmployee->pluck('attend_date')->unique();

            foreach ($attendDates->all() as $dates) {
                $dailyRendered = $attendsByEmployee->where('attend_date', $dates)->sum('render_hours');
                $employeeTotalRenderHours += ($dailyRendered > 8) ? 8 : $dailyRendered;
            }
            array_push($payslips, [
                'cutoff_id' => $this->cutoff->id,
                'employee_id' => $employee->id,
                'basic' => $employeeTotalRenderHours * $employee->currentRate()->hourly_rate,
            ]);
        }
        Payslip::upsert($payslips, ['cutoff_id', 'employee_id'], ['cutoff_id', 'employee_id', 'basic']);
    }
}
