<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Shift extends Model {
    /* Nazev souboru: Shift.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_shifts */

    use HasFactory, Notifiable;
    /* Urceni primarniho klice tabulky, nazvu tabulky */
    protected $primaryKey = 'shift_id';
    protected $table = 'table_shifts';
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'shift_start', 'shift_end','shift_note', 'shift_place','shift_importance_id','company_id'
    ];

    /* Nazev funkce: getConcreteShift
       Argumenty: shift_id - identifikator smeny
       Ucel: ziskani konkretni smeny */
    public static function getConcreteShift($shift_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start',
                'table_shifts.shift_end','table_shifts.shift_place','table_shifts.shift_note',
                'table_shifts.shift_importance_id')
            ->where(['table_shifts.shift_id' => $shift_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->get();
    }

    /* Nazev funkce: getCompanyShifts
        Argumenty: company_id - identifikator firmy
        Ucel: ziskani smen konkretni firmy */
    public static function getCompanyShifts($company_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start',
                'table_shifts.shift_end','table_shifts.shift_place','table_shifts.shift_note',
                'table_shifts.shift_importance_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->get();
    }

    /* Nazev funkce: getCompanyShiftsDesc
        Argumenty: company_id - identifikator firmy
        Ucel: ziskani smen konkretni firmy v obracenem poradi */
    public static function getCompanyShiftsDesc($company_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start',
                'table_shifts.shift_end','table_shifts.shift_place','table_shifts.shift_note',
                'table_shifts.shift_importance_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->orderBy('table_shifts.shift_start', 'desc')
            ->get();
    }

    /* Nazev funkce: getUpcomingCompanyShifts
        Argumenty: company_id - identifikator firmy
        Ucel: ziskani budoucich smen konkretni firmy */
    public static function getUpcomingCompanyShifts($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start',
                'table_shifts.shift_end','table_shifts.shift_place','table_shifts.shift_note',
                'table_shifts.shift_importance_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->orderBy('table_shifts.shift_start', 'asc')
            ->get();
    }

    /* Nazev funkce: getCompanyTotalShiftCount
        Argumenty: company_id - identifikator firmy
        Ucel: ziskani poctu smen konkretni firmy */
    public static function getCompanyTotalShiftCount($company_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->count();
    }

    /* Nazev funkce: getUpcomingCompanyShiftsCount
        Argumenty: company_id - identifikator firmy
        Ucel: ziskani poctu budoucich smen konkretni firmy */
    public static function getUpcomingCompanyShiftsCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->count();
    }

    /* Nazev funkce: getHistoricalCompanyShiftsCount
        Argumenty: company_id - identifikator firmy
        Ucel: ziskani poctu vsech smen konkretni firmy */
    public static function getHistoricalCompanyShiftsCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->where('table_shifts.shift_start', '<',  Carbon::now())
            ->count();
    }

    /* Nazev funkce: getEmployeeShifts
        Argumenty: employee_id - identifikator zamestnance
        Ucel: ziskani vsech prirazenych smen zamestnance */
    public static function getEmployeeShifts($employee_id){
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->get();
    }

    /* Nazev funkce: getCompanyShiftsAssigned
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani vsech prirazenych smen v ramci firmy */
    public static function getCompanyShiftsAssigned($company_id){
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    /* Nazev funkce: getEmployeeShiftsCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu vsech prirazenych smen zamestnance */
    public static function getEmployeeShiftsCount($employee_id){
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->count();
    }

    /* Nazev funkce: getAllEmployeesAtShift
       Argumenty: shift_id - identifikator smeny
       Ucel: ziskani vsech zamestnancu na konkretni smene */
    public static function getAllEmployeesAtShift($shift_id){
        return DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_employees.employee_name','table_employees.employee_surname',
                'table_employees.employee_id','table_employees.employee_position','table_employees.employee_reliability',
                'table_employees.employee_absence','table_employees.employee_workindex')
            ->where(['table_shifts.shift_id' => $shift_id])
            ->orderBy('table_employees.employee_surname', 'asc')
            ->get();
    }

    /* Nazev funkce: getEmployeeUpcomingShiftsCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu budoucich smen konkretniho zamestnance */
    public static function getEmployeeUpcomingShiftsCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->count();
    }

    /* Nazev funkce: getEmployeesCountAtShift
       Argumenty: shift_id - identifikator smeny
       Ucel: ziskani poctu zamestnancu na konkretni smene */
    public static function getEmployeesCountAtShift($shift_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_employee_shifts')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.shift_id' => $shift_id])
            ->count();
    }

    /* Nazev funkce: getEmployeeCurrentShifts
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani aktualnich smen konkretniho zamestnance */
    public static function getEmployeeCurrentShifts($employee_id){
            date_default_timezone_set('Europe/Prague');
            $now = Carbon::now();
            $pondeli = $now->startOfWeek()->format('Y-m-d H:i:s');
            $nedele = $now->endOfWeek()->format('Y-m-d H:i:s');
            return DB::table('table_employee_shifts')
                ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                    'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
                ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
                ->where(['table_employee_shifts.employee_id' => $employee_id])
               ->whereBetween('table_shifts.shift_start', [$pondeli, $nedele])
                ->orderBy('table_shifts.shift_start', 'asc')
                ->distinct()
                ->get();
    }

    /* Nazev funkce: getEmployeeCurrentMonthShifts
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani smen konkretniho zamestnance v ramci aktualniho mesice */
    public static function getEmployeeCurrentMonthShifts($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->whereMonth('table_shifts.shift_start', Carbon::now()->month)
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    /* Nazev funkce: getEmployeeShiftsWithEmployeeInformation
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani prirazenych smen konkretniho zamestnance */
    public static function getEmployeeShiftsWithEmployeeInformation($employee_id){
        return DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->orderByDesc('table_shifts.shift_start')
            ->get();
    }

    /* Nazev funkce: getCurrentImportanceShift
       Argumenty: importance_id - identifikator dulezitosti
       Ucel: ziskani aktualni dulezitosti smeny */
    public static function getCurrentImportanceShift($importance_id){
        return DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->join('table_shifts','table_shifts.shift_importance_id','=','table_importances_shifts.importance_id')
            ->where(['table_shifts.shift_importance_id' => $importance_id])
            ->get();
    }

}
