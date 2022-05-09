<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\Employee;
use App\Computations\DailyOvertime;
use App\Models\Payslip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeOvertimePay implements ShouldQueue
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $employees = Employee::whereHas('overtime', function ($query) {
            $query->whereBetween('overtime_date', [$this->cutoff->from, $this->cutoff->to]);
        })
            ->get();

        foreach ($employees as $employee) {
            $overtimePay = 0;
            foreach ($employee->overtime as $overtime) {
                $overtimePay += (new DailyOvertime($overtime))
                    ->computeAmount();
            }
            Payslip::where('employee_id', $employee->id)
                ->where('cutoff_id', $this->cutoff->id)
                ->update([
                    'overtime' => $overtimePay
                ]);
        }
    }
}
