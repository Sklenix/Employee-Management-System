<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Report
 *
 * @property int $report_id
 * @property string|null $report_title
 * @property string|null $report_description
 * @property string|null $report_note
 * @property int $report_state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $employee_id
 * @property int $report_importance_id
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportImportanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    protected $table = 'table_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'report_title', 'report_description','report_note', 'report_state', 'report_importance_id', 'employee_id'
    ];

    public static function getEmployeeReports($employee_id){
        return DB::table('table_reports')
            ->select('table_reports.report_title','table_reports.report_description','table_reports.report_importance_id',
                'table_reports.report_state','table_reports_importances.importance_report_value', 'table_reports.report_id',
                'table_reports_importances.importance_report_description','table_reports.created_at','table_reports.updated_at')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id' => $employee_id])
            ->orderBy('table_reports.report_importance_id', 'asc')
            ->get();
    }

    public static function getCompanyEmployeesReports($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        return DB::table('table_reports')
            ->select('table_reports.report_title','table_reports.report_description','table_reports.report_importance_id',
                'table_reports.report_state','table_reports_importances.importance_report_value', 'table_reports.report_id',
                'table_reports_importances.importance_report_description','table_reports.created_at','table_reports.updated_at', 'table_employees.employee_name',
                'table_employees.employee_surname','table_employees.employee_picture')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->join('table_employees','table_reports.employee_id','=','table_employees.employee_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereNotIn('table_reports.report_state',[0])
            ->orderBy('table_reports.report_importance_id', 'asc')
            ->get();
    }

    public static function getCompanyReportsCount($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        return DB::table('table_reports')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->count();
    }

    public static function getEmployeeReportsCount($employee_id){
        return DB::table('table_reports')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id' => $employee_id])
            ->count();
    }

    public static function getCompanyReportsByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $nahlaseni = DB::table('table_reports')
            ->select(DB::raw("COUNT(*) as count_reports"))
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereYear('table_reports.created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(table_reports.created_at)"))
            ->pluck('count_reports');

        $mesice_nahlaseni = DB::table('table_reports')
            ->select(DB::raw("Month(table_reports.created_at) as month_report"))
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereYear('table_reports.created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(table_reports.created_at)"))
            ->pluck('month_report');

        $data_reports = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_nahlaseni as $index => $month_shift){
            $data_reports[$month_shift - 1] = $nahlaseni[$index];
        }
        return $data_reports;
    }

    public static function getEmployeeReportsByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $nahlaseni = DB::table('table_reports')
            ->select(DB::raw("COUNT(*) as count_reports"))
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id' => $employee_id])
            ->whereYear('table_reports.created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(table_reports.created_at)"))
            ->pluck('count_reports');

        $mesice_nahlaseni = DB::table('table_reports')
            ->select(DB::raw("Month(table_reports.created_at) as month_report"))
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id' => $employee_id])
            ->whereYear('table_reports.created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(table_reports.created_at)"))
            ->pluck('month_report');

        $data_reports = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_nahlaseni as $index => $month_shift){
            $data_reports[$month_shift - 1] = $nahlaseni[$index];
        }
        return $data_reports;
    }

}
