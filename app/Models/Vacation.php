<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Vacation
 *
 * @property int $vacation_id
 * @property string|null $vacation_start
 * @property string|null $vacation_end
 * @property string|null $vacation_note
 * @property int $vacation_state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $employee_id
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation whereVacationEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation whereVacationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation whereVacationNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation whereVacationStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation whereVacationState($value)
 * @mixin \Eloquent
 */
class Vacation extends Model
{
    use HasFactory;

    protected $primaryKey = 'vacation_id';
    protected $table = 'table_vacations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vacation_start', 'vacation_end','vacation_note', 'vacation_state', 'employee_id'
    ];

    public static function getEmployeeVacations($employee_id){
        return DB::table('table_vacations')
            ->select('table_vacations.vacation_id','table_vacations.vacation_start',
                'table_vacations.vacation_end','table_vacations.vacation_note','table_vacations.vacation_state',
                'table_vacations.created_at','table_vacations.updated_at')
            ->where(['table_vacations.employee_id' => $employee_id])
            ->orderBy('table_vacations.vacation_start', 'asc')
            ->get();
    }

    public static function getEmployeeVacationsCount($employee_id){
        return DB::table('table_vacations')
            ->select('table_vacations.vacation_id','table_vacations.vacation_start',
                'table_vacations.vacation_end','table_vacations.vacation_note','table_vacations.vacation_state',
                'table_vacations.created_at','table_vacations.updated_at')
            ->where(['table_vacations.employee_id' => $employee_id])
            ->orderBy('table_vacations.vacation_start', 'asc')
            ->count();
    }

    public static function getCompanyEmployeesVacations($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        return DB::table('table_vacations')
            ->select('table_vacations.vacation_id','table_vacations.vacation_start',
                'table_vacations.vacation_end','table_vacations.vacation_note','table_vacations.vacation_state',
                'table_vacations.created_at','table_vacations.updated_at','table_employees.employee_name',
                'table_employees.employee_surname','table_employees.employee_picture')
            ->join('table_employees','table_vacations.employee_id','=','table_employees.employee_id')
            ->whereIn('table_vacations.employee_id',$id_zamestnancu)
            ->whereNotIn('table_vacations.vacation_state',[0])
            ->orderBy('table_vacations.vacation_start', 'asc')
            ->get();
    }
}
