<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDimension extends Model
{
    use HasFactory;
    protected $table = 'employee_dimension';
    public $timestamps = false;

    protected $fillable = [
        'employee_id','employee_name','employee_surname','employee_position','employee_overall'
    ];

}
