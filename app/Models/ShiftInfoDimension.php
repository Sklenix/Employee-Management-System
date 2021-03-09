<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftInfoDimension extends Model
{
    use HasFactory;
    protected $table = 'shift_info_dimension';
    public $timestamps = false;

    protected $fillable = [
        'shift_id','shift_start','shift_end','attendance_came','attendance_check_in','attendance_check_out',
        'attendance_check_in_company','attendance_check_out_company'
    ];
}
