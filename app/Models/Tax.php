<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'annual_income_bracket_lower_limit',
        'annual_income_bracket_upper_limit',
        'tax_rate_lower_limit',
        'tax_rate_excess_over_lower_limit'
    ];
}
