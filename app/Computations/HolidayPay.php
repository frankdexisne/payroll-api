<?php

namespace App\Computations;

use App\Models\Holiday;
use App\Models\Attend;
use App\Models\Employee;

class HolidayPay
{
    protected $holiday;

    public function __construct(Holiday $holiday)
    {
        $this->holiday = $holiday;
    }

    public function computeAmount($employeeId, $hourlyRate)
    {
        $totalAcceptedHrs = Attend::where('employee_id', $employeeId)
            ->where('attend_date', $this->holiday->holiday_date)
            ->sum('accepted_hours');
        $percentage = $this->holiday->is_regular ? 1 : 0.3;

        if ($totalAcceptedHrs >= 8) {
            return ($hourlyRate * 8) * $percentage;
        }

        return ($hourlyRate * $totalAcceptedHrs) * $percentage;
    }
}
