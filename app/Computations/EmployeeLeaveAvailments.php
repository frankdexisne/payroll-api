<?php

namespace App\Computations;

use Carbon\Carbon;
use App\Models\Cutoff;
use App\Models\EmployeeLeaveAvailment;

class EmployeeLeaveAvailments
{
    protected $employeeLeaveAvailment;

    public function __construct(EmployeeLeaveAvailment $employeeLeaveAvailment)
    {
        $this->employeeLeaveAvailment = $employeeLeaveAvailment;
    }

    public function pay(Cutoff $cutoff)
    {
        if (!$this->employeeLeaveAvailment->with_pay) {
            return 0;
        }

        $leaveStartDate = Carbon::parse($this->employeeLeaveAvailment->effective_date_from);
        $leaveEndDate = Carbon::parse($this->employeeLeaveAvailment->effective_date_to);
        $cutoffEndDate = Carbon::parse($cutoff->to);

        $dailyRate = $this->employeeLeaveAvailment->employeeLeave->employee->currentRate()->hourly_rate * 8;

        // IF LAST LEAVE DATE IS COVERED OF CUTOFF ENDING DATE
        if ($leaveEndDate <= $cutoffEndDate) {
            return $leaveEndDate->diffInDays($leaveStartDate) * $dailyRate;
        }

        // IF LAST LEAVE DATE IS NOT COVERED OF CUTOFF ENDING DATE
        return $cutoffEndDate->diffInDays($leaveStartDate) * $dailyRate;
    }
}
