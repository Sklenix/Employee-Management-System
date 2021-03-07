<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Disease
 *
 * @property int $disease_id
 * @property string|null $disease_name
 * @property string|null $disease_from
 * @property string|null $disease_to
 * @property int $disease_state
 * @property string|null $disease_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $employee_id
 * @method static \Illuminate\Database\Eloquent\Builder|Disease newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Disease newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Disease query()
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereDiseaseFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereDiseaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereDiseaseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereDiseaseNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereDiseaseState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereDiseaseTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disease whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Disease extends Model
{
    use HasFactory;

    protected $primaryKey = 'disease_id';
    protected $table = 'table_diseases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disease_name', 'disease_from','disease_to', 'disease_state', 'disease_note', 'employee_id'
    ];

    public static function getEmployeeDiseases($employee_id){
        return DB::table('table_diseases')
            ->select('table_diseases.disease_id','table_diseases.disease_name',
                'table_diseases.disease_from','table_diseases.disease_to','table_diseases.disease_state',
                'table_diseases.disease_note','table_diseases.created_at','table_diseases.updated_at')
            ->where(['table_diseases.employee_id' => $employee_id])
            ->orderBy('table_diseases.disease_from', 'asc')
            ->get();
    }

    public static function getEmployeeDiseasesCount($employee_id){
        return DB::table('table_diseases')
            ->select('table_diseases.disease_id','table_diseases.disease_name',
                'table_diseases.disease_from','table_diseases.disease_to','table_diseases.disease_state',
                'table_diseases.disease_note','table_diseases.created_at','table_diseases.updated_at')
            ->where(['table_diseases.employee_id' => $employee_id])
            ->orderBy('table_diseases.disease_from', 'asc')
            ->count();
    }

    public static function getCompanyEmployeesDiseases($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        return DB::table('table_diseases')
            ->select('table_diseases.disease_id','table_diseases.disease_name',
                'table_diseases.disease_from','table_diseases.disease_to','table_diseases.disease_state',
                'table_diseases.disease_note','table_diseases.created_at','table_diseases.updated_at','table_employees.employee_name',
                'table_employees.employee_surname','table_employees.employee_picture')
            ->join('table_employees','table_diseases.employee_id','=','table_employees.employee_id')
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->whereNotIn('table_diseases.disease_state',[0])
            ->orderBy('table_diseases.disease_from', 'asc')
            ->get();

    }
}
