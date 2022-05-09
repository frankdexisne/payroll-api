<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\EmployeeLeaveAvailment;
use App\Models\Payslip;
use App\Computations\EmployeeLeaveAvailments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeLeaves implements ShouldQueue
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
        $availments = EmployeeLeaveAvailment::whereBetween('effective_date_from', [$this->cutoff->from, $this->cutoff->to])
            ->get()->all();
        foreach ($availments as $leaveAvailment) {
            $leave = new EmployeeLeaveAvailments($leaveAvailment);
            $amount = $leave->pay($this->cutoff);
            Payslip::where('cutoff_id', $this->cutoff->id)
                ->where('employee_id', $leaveAvailment->employeeLeave->employee_id)
                ->update([
                    'leave' => $amount
                ]);
        }
    }
}
