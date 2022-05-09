<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\Employee;
use App\Models\Undertime;
use App\Models\Payslip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeUndertimes implements ShouldQueue
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
        $undertime = Undertime::whereBetween('date_commited', [$this->cutoff->from, $this->cutoff->to])
            ->get();
        $employeeIds = collect($undertime->pluck('employee_id')->toArray())->unique()->values()->all();

        $employees = Employee::whereIn('id', $employeeIds)->with(['undertime' => function ($query) {
            $query->whereBetween('date_commited', [$this->cutoff->from, $this->cutoff->to]);
        }])->get();

        foreach ($employees as $employee) {
            Payslip::where('employee_id', $employee->id)
                ->where('cutoff_id', $this->cutoff->id)
                ->update([
                    'undertime' => $employee->undertime->sum('minutes_commited') * ($employee->currentRate()->hourly_rate / 60)
                ]);
        }
    }
}
