<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tardy extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date_commited',
        'minutes_commited'
    ];
}
