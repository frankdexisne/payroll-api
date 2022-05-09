<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }


    public function onDayAttend()
    {
        return Attend::where('employee_id', $this->employee_id)
            ->where('attend_date', $this->overtime_date)
            ->where('shift_id', 3)
            ->sum('render_hours');
    }
}
