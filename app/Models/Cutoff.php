<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cutoff extends Model
{
    use HasFactory;

    protected $fillable = [
        'status'
    ];

    public function payslip()
    {
        return $this->hasMany('App\Models\Payslip');
    }

    public function holidays()
    {
        return Holiday::whereBetween('holiday_date', [$this->from, $this->to])->get();
    }

    public function employeeIdsHasAttendance()
    {
        return Employee::whereHas('attend', function ($query) {
            $query->whereBetween('attend_date', [$this->from, $this->to]);
        })
            ->pluck('id')->toArray();
    }
}
