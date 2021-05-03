<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Employee_Shift
 * @property int $employee_shift_id
 * @property int $employee_id
 * @property int $shift_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Shift whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Shift whereEmployeeShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Shift whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee_Shift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Employee_Shift extends Model {
    /* Nazev souboru: Employee_Shift.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_employee_shifts */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $primaryKey = 'employee_shift_id';
    protected $table = 'table_employee_shifts';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'employee_id', 'shift_id', 'company_user_surname','email','company_phone','company_login','password','company_url','company_picture','company_city','company_street','company_ico','company_dic'
    ];

    /* Nazev funkce: getEmployeeCurrentShifts
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani aktualnich smen zamestnance */
    public static function getEmployeeCurrentShifts($employee_id){
        date_default_timezone_set('Europe/Prague');
        $now = Carbon::now();
        $pondeli = $now->startOfWeek()->format('Y-m-d H:i:s');
        $nedele = $now->endOfWeek()->format('Y-m-d H:i:s');
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id','table_employees.employee_id')
            ->join('table_employees','table_employee_shifts.employee_id','=','table_employees.employee_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->whereBetween('table_shifts.shift_start', [$pondeli, $nedele])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    /* Nazev funkce: getEmployeeAllShifts
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani vsech smen zamestnance */
    public static function getEmployeeAllShifts($employee_id){
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id','table_employees.employee_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_employee_shifts.employee_id','=','table_employees.employee_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    /* Nazev funkce: getEmployeeParticularShift
       Argumenty: employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani konkretni prirazene smeny */
    public static function getEmployeeParticularShift($employee_id, $shift_id){
        return DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_employee_shifts.employee_id','table_employee_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id, 'table_shifts.shift_id' => $shift_id])
            ->get();
    }

    /* Nazev funkce: deleteEmployeeAssignedShiftsWithAttendance
       Argumenty: employee_id - identifikator zamestnance, shift_ids_collector - pole identifikatoru smen
       Ucel: odstraneni vsech budoucich smen spolecne s dochazkou konkretniho zamestnance */
    public static function deleteEmployeeAssignedShiftsWithAttendance($employee_id,$shift_ids_collector){
        date_default_timezone_set('Europe/Prague');
         DB::table('table_employee_shifts')
            ->select('table_employee_shifts.employee_id','table_employee_shifts.shift_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->whereNotIn('table_employee_shifts.shift_id',$shift_ids_collector)
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->delete();

        DB::table('table_attendances')
            ->select('table_attendances.employee_id','table_attendances.shift_id')
            ->join('table_shifts','table_attendances.shift_id','=','table_shifts.shift_id')
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->whereNotIn('table_attendances.shift_id',$shift_ids_collector)
            ->where(['table_attendances.employee_id' => $employee_id])
            ->delete();
    }

    /* Nazev funkce: deleteEmployeeAllUpcomingShiftsWithAttendance
       Argumenty: employee_id - identifikator zamestnance
       Ucel: odstraneni vsech budoucich smen */
    public static function deleteEmployeeAllUpcomingShiftsWithAttendance($employee_id){
        date_default_timezone_set('Europe/Prague');
        DB::table('table_employee_shifts')
            ->select('table_employee_shifts.employee_id','table_employee_shifts.shift_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->delete();

        DB::table('table_attendances')
            ->select('table_attendances.employee_id','table_attendances.shift_id')
            ->join('table_shifts','table_attendances.shift_id','=','table_shifts.shift_id')
            ->where(['table_attendances.employee_id' => $employee_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->delete();
    }

    /* Nazev funkce: deleteAssignedEmployeesShiftWithAttendance
       Argumenty: shift_id - identifikator smeny, employee_ids_collector - pole identifikatoru zamestnance
       Ucel: odstraneni konkretni smeny konkretnim zamestnancum, kteri ji meli prirazenou (spolecne s dochazkou) */
    public static function deleteAssignedEmployeesShiftWithAttendance($shift_id,$employee_ids_collector){
        date_default_timezone_set('Europe/Prague');
        DB::table('table_employee_shifts')
            ->select('table_employee_shifts.employee_id','table_employee_shifts.shift_id')
            ->whereNotIn('employee_id',$employee_ids_collector)
            ->where(['table_employee_shifts.shift_id' => $shift_id])
            ->delete();

        DB::table('table_attendances')
            ->select('table_attendances.employee_id','table_attendances.shift_id')
            ->whereNotIn('table_attendances.employee_id',$employee_ids_collector)
            ->where(['table_attendances.shift_id' => $shift_id])
            ->delete();
    }

    /* Nazev funkce: deleteAllAssignedEmployeesShiftWithAttendance
       Argumenty: shift_id - identifikator smeny, employee_ids_collector - pole identifikatoru zamestnance
       Ucel: odstraneni konkretni smeny vsem zamestnancum, kteri ji meli prirazenou (spolecne s dochazkou) */
    public static function deleteAllAssignedEmployeesShiftWithAttendance($shift_id){
        date_default_timezone_set('Europe/Prague');
        DB::table('table_employee_shifts')
            ->select('table_employee_shifts.employee_id','table_employee_shifts.shift_id')
            ->where(['table_employee_shifts.shift_id' => $shift_id])
            ->delete();
        DB::table('table_attendances')
            ->select('table_attendances.employee_id','table_attendances.shift_id')
            ->where(['table_attendances.shift_id' => $shift_id])
            ->delete();
    }

    /* Nazev funkce: getAttendanceOptionsShifts
       Argumenty: shift_id - identifikator smeny, company_id - identifikator firmy
       Ucel: ziskani udaju pro vyplneni optionsboxu v moznostech dochazky konkretni firmy (seznam smen) */
    public static function getAttendanceOptionsShifts($shift_id, $company_id){
       return DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_employee_shifts.employee_id','table_employees.employee_name','table_employees.employee_surname','table_shifts.shift_start')
            ->where(['table_shifts.shift_id' => $shift_id,'table_shifts.company_id' => $company_id])
            ->orderBy('table_employees.employee_surname', 'asc')
            ->get();
    }

    /* Nazev funkce: getAttendanceOptionsEmployees
       Argumenty: employee_id - identifikator zamestnance, company_id - identifikator firmy
       Ucel: ziskani udaju pro vyplneni optionsboxu v moznostech dochazky konkretni firmy (seznam zamestnancu). V seznamu zamestnancu lze vyplnovat dochazky u smen, ktere jsou z aktualniho mesice */
    public static function getAttendanceOptionsEmployees($employee_id, $company_id){
        return DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_id')
            ->where(['table_employees.employee_id' => $employee_id,'table_employees.employee_company' => $company_id])
            ->whereMonth('table_shifts.shift_start', Carbon::now()->month)
            ->orderBy('table_shifts.shift_start', 'desc')
            ->get();
    }

    /* Nazev funkce: deleteShiftFromShiftDatatable
       Argumenty: shift_id - identifikator smeny
       Ucel: smazani konkretni prirazene smeny */
    public static function deleteShiftFromShiftDatatable($shift_id){
        return DB::table('table_employee_shifts')
            ->select('table_employee_shifts.employee_id','table_employee_shifts.shift_id')
            ->where(['table_employee_shifts.shift_id' => $shift_id])
            ->delete();
    }

    /* Nazev funkce: getEmployeesShiftCounts
       Argumenty: shift_id - identifikator smeny
       Ucel: ziskani poctu zamestnancu s danou prirazenou smenou */
    public static function getEmployeesShiftCounts($shift_id){
        return DB::table('table_employee_shifts')
                ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
                ->select('table_shifts.shift_start','table_shifts.shift_end',
                    'table_shifts.shift_place','table_shifts.shift_id')
                ->where(['table_employee_shifts.shift_id' => $shift_id])
                ->orderByDesc('table_shifts.shift_start')
                ->count();
    }

    /* Nazev funkce: getEmployeeShiftsCounts
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu prirazenych smen pro konkretniho zamestnance */
    public static function getEmployeeShiftsCounts($employee_id){
        return DB::table('table_employee_shifts')
                ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
                ->where(['table_employee_shifts.employee_id' => $employee_id])
                ->count();
    }

    /* Nazev funkce: isShiftTaken
       Argumenty: shift_id - identifikator smeny
       Ucel: indikace, zdali je smena uz zabrana */
    public static function isShiftTaken($shift_id){
        return DB::table('table_employee_shifts')
                ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
                ->select('table_employee_shifts.employee_id')
                ->where([ 'table_shifts.shift_id' => $shift_id])
                ->get();
    }

    /* Nazev funkce: isShiftTaken
       Argumenty: employee_id - identifikator zamestnance
       Ucel: indikace, zdali je smena uz zabrana (smena pouze v budoucnosti) */
    public static function isShiftTakenFuture($employee_id){
        return DB::table('table_employee_shifts')
                ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
                ->select('table_employee_shifts.employee_id')
                ->where([ 'table_employees.employee_id' => $employee_id])
                ->where('table_shifts.shift_start', '>=',  Carbon::now())
                ->get();
    }

}
