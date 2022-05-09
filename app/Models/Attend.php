<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attend extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'attend_date',
        'attend_in',
        'attend_out',
        'render_hours',
        'accepted_hours',
        'remarks'
    ];

    public function shift()
    {
        return $this->belongsTo('App\Models\Shift');
    }
}
