<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Tardy;
use App\Models\Payslip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeLates implements ShouldQueue
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
        $tardy = Tardy::whereBetween('date_commited', [$this->cutoff->from, $this->cutoff->to])
            ->get();
        $employeeIds = collect($tardy->pluck('employee_id')->toArray())->unique()->values()->all();

        $employees = Employee::whereIn('id', $employeeIds)->with(['tardy' => function ($query) {
            $query->whereBetween('date_commited', [$this->cutoff->from, $this->cutoff->to]);
        }])->get();

        foreach ($employees as $employee) {
            Payslip::where('employee_id', $employee->id)
                ->where('cutoff_id', $this->cutoff->id)
                ->update([
                    'late' => $employee->tardy->sum('minutes_commited') * ($employee->currentRate()->hourly_rate / 60)
                ]);
        }
    }
}
