<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftFacts extends Model
{
    use HasFactory;
    protected $table = 'shift_info_dimension';
    public $timestamps = false;

    protected $fillable = [
        'shift_total_hours','absence_total_count','absence_total_hours','average_employee_score','company_id','time_id',
        'employee_id','shift_id'
    ];
}
