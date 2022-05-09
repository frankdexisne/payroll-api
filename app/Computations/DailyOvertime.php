<?php

namespace App\Computations;

use App\Models\Overtime;

class DailyOvertime
{
    protected $overtime;

    public function __construct(Overtime $overtime)
    {
        $this->overtime = $overtime;
    }

    public function computeAmount()
    {
        $currentRate = $this->overtime->employee->currentRate()->hourly_rate;
        return $this->overtime->onDayAttend() * ($currentRate + ($currentRate * 0.3));
    }
}
