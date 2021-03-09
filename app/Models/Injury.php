<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Injury
 *
 * @property int $injury_id
 * @property string|null $injury_description
 * @property string|null $injury_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $employee_id
 * @property int $shift_id
 * @method static \Illuminate\Database\Eloquent\Builder|Injury newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Injury newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Injury query()
 * @method static \Illuminate\Database\Eloquent\Builder|Injury whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Injury whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Injury whereInjuryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Injury whereInjuryDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Injury whereInjuryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Injury whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Injury whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Injury extends Model
{
    use HasFactory;

    protected $primaryKey = 'injury_id';
    protected $table = 'table_injuries';
    protected $guard = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'injury_description', 'injury_date','employee_id', 'shift_id'
    ];

    public static function getInjuries($company_id){
        return DB::table('table_injuries')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_place',
                'table_employees.employee_name','table_employees.employee_surname','table_employees.employee_id',
                'table_injuries.injury_description','table_injuries.injury_date','table_injuries.created_at','table_injuries.updated_at','table_injuries.injury_id',
                'table_employees.employee_picture')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->orderBy('table_shifts.shift_end', 'asc')
            ->distinct()
            ->get();
    }

    public static function getEmployeeInjuries($company_id,$employee_id,$shift_id){
        return DB::table('table_injuries')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_place',
                'table_employees.employee_name','table_employees.employee_surname','table_employees.employee_id',
                'table_injuries.injury_description','table_injuries.injury_date','table_injuries.created_at','table_injuries.updated_at','table_injuries.injury_id',
                'table_employees.employee_picture')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_shifts.company_id' => $company_id,'table_employees.employee_id' => $employee_id,'table_shifts.shift_id' => $shift_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->orderBy('table_shifts.shift_end', 'asc')
            ->distinct()
            ->get();
    }

    public static function getEmployeeInjuriesInjuryCentre($employee_id){
        return DB::table('table_injuries')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_place',
                'table_employees.employee_id', 'table_injuries.injury_description','table_injuries.injury_date','table_injuries.created_at',
                'table_injuries.updated_at','table_injuries.injury_id')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->orderBy('table_shifts.shift_end', 'asc')
            ->distinct()
            ->get();
    }

    public static function getEmployeeInjuriesInjuryCentreCount($employee_id){
        return DB::table('table_injuries')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_place',
                'table_employees.employee_id', 'table_injuries.injury_description','table_injuries.injury_date','table_injuries.created_at',
                'table_injuries.updated_at','table_injuries.injury_id')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->orderBy('table_shifts.shift_end', 'asc')
            ->distinct()
            ->count();
    }


}
