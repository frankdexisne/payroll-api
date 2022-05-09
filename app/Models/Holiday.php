<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    public function employeesOnDuty()
    {
        return Employee::whereHas('attend', function ($query) {
            $query->where('attend_date', $this->holiday_date);
        })
            ->get();
    }
}
