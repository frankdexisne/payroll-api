<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $appends = [
        'full_name'
    ];

    public function getFullNameAttribute()
    {
        return $this->lname . ', ' . $this->fname . ' ' . $this->mname;
    }

    public function employeeLeave()
    {
        return $this->hasMany('App\Models\EmployeeLeave');
    }

    public function rate()
    {
        return $this->hasMany('App\Models\Rate');
    }

    public function family()
    {
        return $this->hasMany('App\Models\Family');
    }

    public function educationalBackground()
    {
        return $this->hasMany('App\Models\EducationalBackground');
    }

    public function address()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function currentRate()
    {
        return Rate::where('employee_id', $this->id)->orderBy('effectivity_date', 'DESC')->first();
    }

    public function payslipId($cutoffId)
    {
        return Payslip::where('employee_id', $this->id)
            ->where('cutoff_id', $cutoffId)
            ->pluck('id')[0];
    }

    public function attend()
    {
        return $this->hasMany('App\Models\Attend');
    }

    public function overtime()
    {
        return $this->hasMany('App\Models\Overtime');
    }

    public function tardy()
    {
        return $this->hasMany('App\Models\Tardy');
    }

    public function undertime()
    {
        return $this->hasMany('App\Models\Undertime');
    }

    public function absence()
    {
        return $this->hasMany('App\Models\Absence');
    }

    public function totalCutoffAcceptedHours($start, $end)
    {
        return Attend::where('employee_id', $this->id)
            ->whereBetween('attend_date', [$start, $end])
            ->sum('accepted_hours');
    }
}
