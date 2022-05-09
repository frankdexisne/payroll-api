<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveAvailment extends Model
{
    use HasFactory;

    public function employeeLeave()
    {
        return $this->belongsTo('App\Models\EmployeeLeave');
    }
}
