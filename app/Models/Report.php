<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model {
    /* Nazev souboru: Report.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_reports */
    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky */
    protected $primaryKey = 'report_id';
    protected $table = 'table_reports';
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'report_title', 'report_description','report_note', 'report_state', 'report_importance_id', 'employee_id'
    ];

    /* Nazev funkce: getEmployeeReports
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani vsech nahlaseni konkretniho zamestnance */
    public static function getEmployeeReports($employee_id){
        return DB::table('table_reports')
            ->select('table_reports.report_title','table_reports.report_description','table_reports.report_importance_id',
                'table_reports.report_state', 'table_reports.report_id',
                'table_reports_importances.importance_report_description','table_reports.created_at','table_reports.updated_at')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id' => $employee_id])
            ->orderBy('table_reports.report_importance_id', 'asc')
            ->get();
    }

    /* Nazev funkce: getEmployeeReports
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani vsech nahlaseni zamestnancu v ramci konkretni firmy */
    public static function getCompanyEmployeesReports($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        return DB::table('table_reports')
            ->select('table_reports.report_title','table_reports.report_description','table_reports.report_importance_id',
                'table_reports.report_state', 'table_reports.report_id',
                'table_reports_importances.importance_report_description','table_reports.created_at','table_reports.updated_at', 'table_employees.employee_name',
                'table_employees.employee_surname','table_employees.employee_picture')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->join('table_employees','table_reports.employee_id','=','table_employees.employee_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereNotIn('table_reports.report_state',[0])
            ->orderBy('table_reports.report_importance_id', 'asc')
            ->get();
    }

    /* Nazev funkce: getCompanyReportsCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu vsech nahlaseni zamestnancu v ramci konkretni firmy */
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

    /* Nazev funkce: getEmployeeReportsCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu vsech nahlaseni konkretniho zamestnance */
    public static function getEmployeeReportsCount($employee_id){
        return DB::table('table_reports')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id' => $employee_id])
            ->count();
    }

    /* Nazev funkce: getCompanyReportsByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu nahlaseni zamestnancu v konkretni firme dle mesicu */
    public static function getCompanyReportsByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $nahlaseni = DB::table('table_reports')
            ->selectRaw('COUNT(*) as count_reports')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereYear('table_reports.created_at', Carbon::now()->year)
            ->groupByRaw('MONTH(table_reports.created_at)')
            ->get();
        $mesice_nahlaseni = DB::table('table_reports')
            ->selectRaw('MONTH(table_reports.created_at) as month_report')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereYear('table_reports.created_at', Carbon::now()->year)
            ->groupByRaw('MONTH(table_reports.created_at)')
            ->get();
        $statistikaNahlaseni = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_nahlaseni); $i++){
            $statistikaNahlaseni[$mesice_nahlaseni[$i]->month_report - 1] = $nahlaseni[$i]->count_reports;
        }
        return $statistikaNahlaseni;
    }

    /* Nazev funkce: getEmployeeReportsByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu nahlaseni konkretniho zamestnance dle mesicu */
    public static function getEmployeeReportsByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $nahlaseni = DB::table('table_reports')
            ->selectRaw('COUNT(*) as count_reports')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id' => $employee_id])
            ->whereYear('table_reports.created_at', Carbon::now()->year)
            ->groupByRaw('MONTH(table_reports.created_at)')
            ->get();
        $mesice_nahlaseni = DB::table('table_reports')
            ->selectRaw('MONTH(table_reports.created_at) as month_report')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id' => $employee_id])
            ->whereYear('table_reports.created_at', Carbon::now()->year)
            ->groupByRaw('MONTH(table_reports.created_at)')
            ->get();
        $statistikaNahlaseni = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_nahlaseni); $i++){
            $statistikaNahlaseni[$mesice_nahlaseni[$i]->month_report - 1] = $nahlaseni[$i]->count_reports;
        }
        return $statistikaNahlaseni;
    }

}
