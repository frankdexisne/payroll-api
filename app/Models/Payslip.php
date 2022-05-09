<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'cutoff_id',
        'employee_id',
        'no_of_days',
        'basic',
        'overtime',
        'holiday',
        'leave',
        'gross',
        'adjustment_add',
        'adjustment_less',
        'late',
        'undertime',
        'sss',
        'philhealth',
        'pagibig',
        'tax',
        'sss_loan',
        'pagibig_loan'
    ];

    protected $appends = [
        'basic_amount',
        'overtime_amount',
        'holiday_amount',
        'leave_amount',
        'gross_amount',
        'adjustment_add_amount',
        'adjustment_less_amount',
        'late_amount',
        'undertime_amount',
        'sss_amount',
        'philhealth_amount',
        'hdmf_amount',
        'tax_amount',
        'sss_loan_amount',
        'pagibig_loan_amount',
        'net_amount'
    ];

    public function getBasicAmountAttribute()
    {
        return number_format($this->basic, 2, '.', ',');
    }

    public function getOvertimeAmountAttribute()
    {
        return number_format($this->overtime, 2, '.', ',');
    }

    public function getHolidayAmountAttribute()
    {
        return number_format($this->holiday, 2, '.', ',');
    }

    public function getLeaveAmountAttribute()
    {
        return number_format($this->leave, 2, '.', ',');
    }

    public function getGrossAmountAttribute()
    {
        return number_format($this->gross, 2, '.', ',');
    }

    public function getAdjustmentAddAmountAttribute()
    {
        return number_format($this->adjustment_add, 2, '.', ',');
    }

    public function getAdjustmentLessAmountAttribute()
    {
        return number_format($this->adjustment_less, 2, '.', ',');
    }

    public function getLateAmountAttribute()
    {
        return number_format($this->late, 2, '.', ',');
    }

    public function getUndertimeAmountAttribute()
    {
        return number_format($this->undertime, 2, '.', ',');
    }

    public function getSssAmountAttribute()
    {
        return number_format($this->sss, 2, '.', ',');
    }

    public function getPhilhealthAmountAttribute()
    {
        return number_format($this->philhealth, 2, '.', ',');
    }

    public function getHdmfAmountAttribute()
    {
        return number_format($this->pagibig, 2, '.', ',');
    }

    public function getTaxAmountAttribute()
    {
        return number_format($this->tax, 2, '.', ',');
    }

    public function getSssLoanAmountAttribute()
    {
        return number_format($this->sss_loan, 2, '.', ',');
    }

    public function getPagibigLoanAmountAttribute()
    {
        return number_format($this->pagibig_loan, 2, '.', ',');
    }

    public function getNetAmountAttribute()
    {
        $net = $this->gross - ($this->sss + $this->pagibig + $this->philhealth + $this->tax + $this->late + $this->undertime + $this->adjustment_less);
        return number_format($net, 2, '.', ',');
    }

    public function cutoff()
    {
        return $this->belongsTo('App\Models\Cutoff');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function deduction()
    {
        return $this->belongsTo('App\Models\Deduction');
    }

    public function getGrossPayAttribute()
    {
        return $this->basic + $this->overtime + $this->holiday + $this->leave + $this->adjustment;
    }
}
