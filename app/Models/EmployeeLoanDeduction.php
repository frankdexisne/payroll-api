<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLoanDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'payslip_id',
        'employee_loan_id',
        'deduct_at',
        'amount',
    ];
}
