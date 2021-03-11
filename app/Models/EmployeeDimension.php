<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmployeeDimension
 *
 * @property int $employee_id
 * @property string $employee_name
 * @property string $employee_surname
 * @property string $employee_position
 * @property string|null $employee_overall
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeeOverall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDimension whereEmployeeSurname($value)
 * @mixin \Eloquent
 */
class EmployeeDimension extends Model
{
    use HasFactory;
    protected $table = 'employee_dimension';
    public $timestamps = false;

    protected $fillable = [
        'employee_id','employee_name','employee_surname','employee_position','employee_overall'
    ];

}
