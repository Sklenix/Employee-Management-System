<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'employee_id';
    protected $table = 'table_employees';
    protected $guard = 'employees';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'employee_name', 'employee_surname','employee_phone', 'email','employee_note','employee_position','employee_city','employee_street',
        'employee_reliability','employee_absence','employee_workindex','employee_drive_url','employee_login','password','employee_company','employee_picture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



}
