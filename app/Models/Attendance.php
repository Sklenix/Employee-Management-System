<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

/**
 * App\Models\Attendance
 * Tato trida je modelem k tabulce table_attendances
 * @property int $attendance_id
 * @property int $attendance_came
 * @property string|null $attendance_note
 * @property string|null $attendance_check_in
 * @property string|null $attendance_check_out
 * @property string|null $attendance_check_in_company
 * @property string|null $attendance_check_out_company
 * @property int|null $absence_reason_id
 * @property int|null $employee_id
 * @property int|null $shift_id
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAbsenceReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAttendanceCame($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAttendanceCheckIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAttendanceCheckInCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAttendanceCheckOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAttendanceCheckOutCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAttendanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAttendanceNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereShiftId($value)
 * @mixin \Eloquent
 */
class Attendance extends Model {
    /* Nazev souboru: Attendance.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_attendances */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $primaryKey = 'attendance_id';
    protected $table = 'table_attendances';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'attendance_came', 'attendance_note','attendance_check_in', 'attendance_check_out','attendance_check_in_company','attendance_check_out_company',
        'absence_reason_id','employee_id', 'shift_id'
    ];

    /* Nazev funkce: getEmployeeAbsenceCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu absenci zamestnance */
    public static function getEmployeeAbsenceCount($employee_id){
        if (DB::table('table_attendances')->where('table_attendances.employee_id', $employee_id)->exists()) {
            return DB::table('table_attendances')
                    ->select('table_shifts.shift_id')
                    ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                    ->where(['table_attendances.employee_id' => $employee_id])
                    ->whereIn('table_attendances.absence_reason_id', [1,2,3])
                    ->count();
        }else{
            return 0;
        }
    }

    /* Nazev funkce: getCompanyAbsenceCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu absenci zamestnancu v ramci firmy */
    public static function getCompanyAbsenceCount($company_id){
        $zamestnanci = Employee::where(['employee_company' => $company_id])->get();
        $seznam_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($seznam_zamestnancu,$zamestnanec->employee_id);
        }
        if (DB::table('table_attendances')->whereIn('table_attendances.employee_id', $seznam_zamestnancu)->exists()) {
            return DB::table('table_attendances')
                ->select('table_shifts.shift_id')
                ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                ->whereIn('table_attendances.employee_id', $seznam_zamestnancu)
                ->whereIn('table_attendances.absence_reason_id', [1,2,3])
                ->count();
        }else{
            return 0;
        }
    }

    /* Nazev funkce: getCompanyAbsenceLateCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu zpozdeni zamestnancu v ramci firmy */
    public static function getCompanyAbsenceLateCount($company_id){
        $zamestnanci = Employee::where(['employee_company' => $company_id])->get();
        $seznam_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($seznam_zamestnancu,$zamestnanec->employee_id);
        }
        if (DB::table('table_attendances')->whereIn('table_attendances.employee_id', $seznam_zamestnancu)->exists()) {
            return DB::table('table_attendances')
                ->select('table_shifts.shift_id')
                ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                ->whereIn('table_attendances.employee_id', $seznam_zamestnancu)
                ->whereIn('table_attendances.absence_reason_id', [4])
                ->count();
        }else{
            return 0;
        }
    }

    /* Nazev funkce: getCompanyAbsenceDiseaseCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu nemoci zamestnancu v ramci firmy */
    public static function getCompanyAbsenceDiseaseCount($company_id){
        $zamestnanci = Employee::where(['employee_company' => $company_id])->get();
        $seznam_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($seznam_zamestnancu,$zamestnanec->employee_id);
        }
        if (DB::table('table_attendances')->whereIn('table_attendances.employee_id', $seznam_zamestnancu)->exists()) {
            return DB::table('table_attendances')
                ->select('table_shifts.shift_id')
                ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                ->whereIn('table_attendances.employee_id', $seznam_zamestnancu)
                ->whereIn('table_attendances.absence_reason_id', [1])
                ->count();
        }else{
            return 0;
        }
    }

    /* Nazev funkce: getCompanyAbsenceNotCameCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu neprichodu zamestnancu v ramci firmy */
    public static function getCompanyAbsenceNotCameCount($company_id){
        $zamestnanci = Employee::where(['employee_company' => $company_id])->get();
        $seznam_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($seznam_zamestnancu,$zamestnanec->employee_id);
        }
        if (DB::table('table_attendances')->whereIn('table_attendances.employee_id', $seznam_zamestnancu)->exists()) {
            return DB::table('table_attendances')
                ->select('table_shifts.shift_id')
                ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                ->whereIn('table_attendances.employee_id', $seznam_zamestnancu)
                ->whereIn('table_attendances.absence_reason_id', [2])
                ->count();
        }else{
            return 0;
        }
    }

    /* Nazev funkce: getCompanyAbsenceDeniedCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu odmitnuti zamestnancu v ramci firmy */
    public static function getCompanyAbsenceDeniedCount($company_id){
        $zamestnanci = Employee::where(['employee_company' => $company_id])->get();
        $seznam_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($seznam_zamestnancu,$zamestnanec->employee_id);
        }
        if (DB::table('table_attendances')->whereIn('table_attendances.employee_id', $seznam_zamestnancu)->exists()) {
            return DB::table('table_attendances')
                ->select('table_shifts.shift_id')
                ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                ->whereIn('table_attendances.employee_id', $seznam_zamestnancu)
                ->whereIn('table_attendances.absence_reason_id' , [3])
                ->count();
        }else{
            return 0;
        }
    }

    /* Nazev funkce: getAttendanceAbsenceDiseaseByMonths
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: ziskani poctu nemoci zamestnancu v ramci firmy dle mesicu */
    public static function getAttendanceAbsenceDiseaseByMonths($company_id, $rok){
        $dochazka = DB::table('table_attendances')
                    ->selectRaw('COUNT(*) as count_attendances')
                    ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                    ->where('company_id', $company_id)
                    ->whereYear('shift_start', $rok)
                    ->whereIn('table_attendances.absence_reason_id', [1])
                    ->groupByRaw('MONTH(shift_start)')
                    ->get();
        $mesice_dochazka = DB::table('table_attendances')
                    ->selectRaw('MONTH(shift_start) as month_attendances')
                    ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                    ->where('company_id', $company_id)
                    ->whereYear('shift_start', $rok)
                    ->whereIn('table_attendances.absence_reason_id', [1])
                    ->groupByRaw('MONTH(shift_start)')
                    ->get();
        $statistikaDochazka = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_dochazka); $i++){
            $statistikaDochazka[$mesice_dochazka[$i]->month_attendances - 1] = $dochazka[$i]->count_attendances;
        }
        return $statistikaDochazka;
    }

    /* Nazev funkce: getAttendanceAbsenceNotComeByMonths
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: ziskani poctu neprichodu zamestnancu v ramci firmy dle mesicu */
    public static function getAttendanceAbsenceNotComeByMonths($company_id, $rok){
        $dochazka = DB::table('table_attendances')
                    ->selectRaw('COUNT(*) as count_attendances')
                    ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                    ->where('company_id', $company_id)
                    ->whereYear('shift_start', $rok)
                    ->whereIn('table_attendances.absence_reason_id', [2])
                    ->groupByRaw('MONTH(shift_start)')
                    ->get();
        $mesice_dochazka = DB::table('table_attendances')
                    ->selectRaw('MONTH(shift_start) as month_attendances')
                    ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                    ->where('company_id', $company_id)
                    ->whereYear('shift_start', $rok)
                    ->whereIn('table_attendances.absence_reason_id', [2])
                    ->groupByRaw('MONTH(shift_start)')
                    ->get();
        $statistikaDochazka = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_dochazka); $i++){
            $statistikaDochazka[$mesice_dochazka[$i]->month_attendances - 1] = $dochazka[$i]->count_attendances;
        }
        return $statistikaDochazka;
    }

    /* Nazev funkce: getAttendanceAbsenceDeniedByMonths
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: ziskani poctu odmitnuti zamestnancu v ramci firmy dle mesicu */
    public static function getAttendanceAbsenceDeniedByMonths($company_id, $rok){
        $dochazka = DB::table('table_attendances')
                        ->selectRaw('COUNT(*) as count_attendances')
                        ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                        ->where('company_id', $company_id)
                        ->whereYear('shift_start', $rok)
                        ->whereIn('table_attendances.absence_reason_id', [3])
                        ->groupByRaw('MONTH(shift_start)')
                        ->get();
        $mesice_dochazka = DB::table('table_attendances')
                        ->selectRaw('MONTH(shift_start) as month_attendances')
                        ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                        ->where('company_id', $company_id)
                        ->whereYear('shift_start', $rok)
                        ->whereIn('table_attendances.absence_reason_id', [3])
                        ->groupByRaw('MONTH(shift_start)')
                        ->get();
        $statistikaDochazka = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_dochazka); $i++){
            $statistikaDochazka[$mesice_dochazka[$i]->month_attendances - 1] = $dochazka[$i]->count_attendances;
        }
        return $statistikaDochazka;
    }

    /* Nazev funkce: getAttendanceAbsenceDelayByMonths
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: ziskani poctu zpozdeni zamestnancu v ramci firmy dle mesicu */
    public static function getAttendanceAbsenceDelayByMonths($company_id,$rok){
        $dochazka = DB::table('table_attendances')
            ->selectRaw('COUNT(*) as count_attendances')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->where('company_id', $company_id)
            ->whereYear('shift_start', $rok)
            ->whereIn('table_attendances.absence_reason_id', [4])
            ->groupByRaw('MONTH(shift_start)')
            ->get();
        $mesice_dochazka = DB::table('table_attendances')
            ->selectRaw('MONTH(shift_start) as month_attendances')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->where('company_id', $company_id)
            ->whereYear('shift_start', $rok)
            ->whereIn('table_attendances.absence_reason_id', [4])
            ->groupByRaw('MONTH(shift_start)')
            ->get();
        $statistikaDochazka = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_dochazka); $i++){
            $statistikaDochazka[$mesice_dochazka[$i]->month_attendances - 1] = $dochazka[$i]->count_attendances;
        }
        return $statistikaDochazka;
    }

    /* Nazev funkce: getAttendanceOkByMonths
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: ziskani poctu smen v poradku zamestnancu v ramci firmy dle mesicu */
    public static function getAttendanceOkByMonths($company_id, $rok){
        $dochazka = DB::table('table_attendances')
                    ->selectRaw('COUNT(*) as count_attendances')
                    ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                    ->where('company_id', $company_id)
                    ->whereYear('shift_start', $rok)
                    ->whereIn('table_attendances.absence_reason_id', [5])
                    ->groupByRaw('MONTH(shift_start)')
                    ->get();
        $mesice_dochazka = DB::table('table_attendances')
                    ->selectRaw('MONTH(shift_start) as month_attendances')
                    ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                    ->where('company_id', $company_id)
                    ->whereYear('shift_start', $rok)
                    ->whereIn('table_attendances.absence_reason_id', [5])
                    ->groupByRaw('MONTH(shift_start)')
                    ->get();
        $statistikaDochazka = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_dochazka); $i++){
            $statistikaDochazka[$mesice_dochazka[$i]->month_attendances - 1] = $dochazka[$i]->count_attendances;
        }
        return $statistikaDochazka;
    }

    /* Nazev funkce: getCompanyAbsenceOKCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu dochazek v poradku v ramci firmy */
    public static function getCompanyAbsenceOKCount($company_id){
        $zamestnanci = Employee::where(['employee_company' => $company_id])->get();
        $seznam_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($seznam_zamestnancu,$zamestnanec->employee_id);
        }
        if (DB::table('table_attendances')->whereIn('table_attendances.employee_id', $seznam_zamestnancu)->exists()) {
            return DB::table('table_attendances')
                ->select('table_shifts.shift_id')
                ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                ->whereIn('table_attendances.employee_id', $seznam_zamestnancu)
                ->whereIn('table_attendances.absence_reason_id', [5])
                ->count();
        }else{
            return 0;
        }
    }

    /* Nazev funkce: getEmployeeShiftsCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu prirazenych smen zamestnance (s vyplnenou dochazkou) */
    public static function getEmployeeShiftsCount($employee_id){
        if (DB::table('table_attendances')->where('table_attendances.employee_id', $employee_id)->exists()) {
            return DB::table('table_attendances')
                    ->select('table_shifts.shift_id')
                    ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                    ->where(['table_attendances.employee_id' => $employee_id])
                    ->count();
        }else{
            return 0;
        }
    }

    /* Nazev funkce: getEmployeeCurrentShiftAbsenceStatus
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani statusu dochazky konkretni smeny */
    public static function getEmployeeCurrentShiftAbsenceStatus($shift_id, $employee_id){
       return DB::table('table_absence_reasons')
                ->select('table_absence_reasons.reason_description','table_attendances.absence_reason_id')
                ->join('table_attendances','table_absence_reasons.reason_id','=','table_attendances.absence_reason_id')
                ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
    }

    /* Nazev funkce: getEmployeeCheckIn
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani zapisu prichodu (zapsane zamestnancem) */
    public static function getEmployeeCheckIn($shift_id,$employee_id){
        return DB::table('table_attendances')
                ->select('table_attendances.attendance_check_in')
                ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
    }

    /* Nazev funkce: getEmployeeCheckOut
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani zapisu odchodu (zapsane zamestnancem) */
    public static function getEmployeeCheckOut($shift_id,$employee_id){
        return DB::table('table_attendances')
                ->select('table_attendances.attendance_check_out')
                ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
    }

    /* Nazev funkce: getCompanyCheckIn
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani zapisu prichodu (zapsane firmou) */
    public static function getCompanyCheckIn($shift_id,$employee_id){
        return DB::table('table_attendances')
                ->select('table_attendances.attendance_check_in_company')
                ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
    }

    /* Nazev funkce: getCompanyCheckIn
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani zapisu odchodu (zapsane firmou) */
    public static function getCompanyCheckOut($shift_id,$employee_id){
        return DB::table('table_attendances')
                ->select('table_attendances.attendance_check_out_company')
                ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
    }

    /* Nazev funkce: getAttendanceCame
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani indikatoru prichodu v ramci dochazky */
    public static function getAttendanceCame($shift_id,$employee_id){
        return DB::table('table_attendances')
                ->select('table_attendances.attendance_came')
                ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
    }

    /* Nazev funkce: getAllCheckInCheckOutForShift
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani prichodu a odchodu zapsanou firmou i zamestnancem */
    public static function getAllCheckInCheckOutForShift($shift_id,$employee_id){
        return DB::table('table_attendances')
                ->select('table_attendances.attendance_check_out','table_attendances.attendance_check_in',
                    'table_attendances.attendance_check_out_company','table_attendances.attendance_check_in_company')
                ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
    }

    /* Nazev funkce: getEmployeeShiftParticularAttendance
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani konkretni dochazky */
    public static function getEmployeeShiftParticularAttendance($shift_id, $employee_id){
        return DB::table('table_attendances')
            ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
            ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
            ->select('table_attendances.attendance_check_in_company','table_attendances.attendance_check_out_company',
                'table_attendances.attendance_check_in', 'table_attendances.attendance_check_out', 'table_attendances.absence_reason_id',
                'table_attendances.attendance_note', 'table_attendances.attendance_came')
            ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
            ->get();
    }

    /* Nazev funkce: getEmployeeShifts
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani prirazenych smen zamestnance (s vyplnenou dochazkou) */
    public static function getEmployeeShifts($employee_id){
        return DB::table('table_attendances')
                ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                    'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
                ->join('table_shifts','table_attendances.shift_id','=','table_shifts.shift_id')
                ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                ->where(['table_attendances.employee_id' => $employee_id])
                ->get();
    }

}
