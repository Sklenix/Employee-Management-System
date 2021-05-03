<?php

namespace App\Http\Controllers;

use App\Models\CompanyDimension;
use App\Models\EmployeeDimension;
use App\Models\ShiftFacts;
use App\Models\ShiftInfoDimension;
use App\Models\TimeDimension;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;

class OlapETL extends Controller {
    /* Nazev souboru:  OlapETL.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k extrakcim, transformacim a ukladanim v ramci OLAP sekce systemu. */

    /* Nazev funkce: extractDataToShiftInfoDimension
       Argumenty: shift - konkretni smena
       Ucel: vytvoreni zaznamu v dimenzi shift_info_dimension */
    public static function extractDataToShiftInfoDimension($shift) {
        $new_shift_info_dimension = ShiftInfoDimension::create(['shift_start' => $shift->shift_start, 'shift_end' => $shift->shift_end, 'attendance_check_in' => NULL, 'attendance_check_out' => NULL, 'attendance_check_in_company' => NULL, 'attendance_check_out_company' => NULL]);
        return $new_shift_info_dimension->shift_info_id;
    }

    /* Nazev funkce: extractDataToTimeDimension
       Argumenty: shift_info_id - jednoznacnacny identifikator smeny v ramci shift_info_dimension, shift - konkretni smena
       Ucel: vytvoreni zaznamu v dimenzi time_dimension (extrakce dnu, mesice, ctvrtleti a roku) */
    public static function extractDataToTimeDimension($shift_info_id, $shift) {
        $new_time_dimension = TimeDimension::create(['time_id' => $shift_info_id, 'day' => Carbon::parse($shift->shift_start)->day, 'month' => Carbon::parse($shift->shift_start)->month, 'quarter' => Carbon::parse($shift->shift_start)->quarter, 'year' => Carbon::parse($shift->shift_start)->year]);
        return $new_time_dimension->time_id;
    }

    /* Nazev funkce: extractDataToEmployeeDimension
       Argumenty: employee - konkretni zamestnanec
       Ucel: vytvoreni zaznamu v dimenzi employee_dimension */
    public static function extractDataToEmployeeDimension($employee) {
        $new_employee_dimension = EmployeeDimension::firstOrCreate(['employee_id' => $employee->employee_id], ['employee_id' => $employee->employee_id, 'employee_name' => $employee->employee_name, 'employee_surname' => $employee->employee_surname, 'employee_position' => $employee->employee_position, 'employee_overall' => $employee->employee_overall]);
        return $new_employee_dimension->employee_id;
    }

    /* Nazev funkce: extractDataToCompanyDimension
       Argumenty: user - konkretni firma
       Ucel: vytvoreni zaznamu v dimenzi company_dimension */
    public static function extractDataToCompanyDimension($user) {
        $new_company_dimension = CompanyDimension::firstOrCreate(['company_id' => $user->company_id], ['company_id' => $user->company_id, 'company_name' => $user->company_name, 'company_city' => $user->company_city, 'company_street' => $user->company_street, 'company_user_name' => $user->company_user_name, 'company_user_surname' => $user->company_user_surname]);
        return $new_company_dimension->company_id;
    }

    /* Nazev funkce: getShiftInfoId
       Argumenty: employee_id - identifikator zamestnance, company_id - identifikator firmy, shift_start - zacatek smeny, shift_end - konec smeny
       Ucel: ziskani ID smeny v ramci OLAP sekce systemu */
    public static function getShiftInfoId($employee_id, $company_id, $shift_start, $shift_end) {
        $smena = '';
        if ($company_id == NULL) {
            $smena = DB::table('shift_facts')
                ->select('shift_info_dimension.shift_info_id')
                ->join('shift_info_dimension', 'shift_facts.shift_info_id', '=', 'shift_info_dimension.shift_info_id')
                ->where(['shift_info_dimension.shift_start' => $shift_start])
                ->where(['shift_info_dimension.shift_end' => $shift_end])
                ->where(['shift_facts.employee_id' => $employee_id])
                ->first();
        } else {
            $smena = DB::table('shift_facts')
                ->select('shift_info_dimension.shift_info_id')
                ->join('shift_info_dimension', 'shift_facts.shift_info_id', '=', 'shift_info_dimension.shift_info_id')
                ->where(['shift_info_dimension.shift_start' => $shift_start])
                ->where(['shift_info_dimension.shift_end' => $shift_end])
                ->where(['shift_facts.employee_id' => $employee_id])
                ->where(['shift_facts.company_id' => $company_id])
                ->first();
        }
        return $smena->shift_info_id;
    }

    /* Nazev funkce: extractAttendanceCheckInCompanyToShiftInfoDimension
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, time - cas prichodu zapsany firmou
       Ucel: zapsani prichodu (zapsane firmou) do shift_info_dimension */
    public static function extractAttendanceCheckInCompanyToShiftInfoDimension($shift_info_id, $time) {
        ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['attendance_check_in_company' => $time]);
    }

    /* Nazev funkce: extractAttendanceCheckOutCompanyToShiftInfoDimension
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, time - cas odchodu zapsany firmou
       Ucel: zapsani odchodu (zapsane firmou) do shift_info_dimension */
    public static function extractAttendanceCheckOutCompanyToShiftInfoDimension($shift_info_id, $time) {
        ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['attendance_check_out_company' => $time]);
    }

    /* Nazev funkce: extractAttendanceCheckInToShiftInfoDimension
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, time - cas prichodu zapsany zamestnancem
       Ucel: zapsani prichodu (zapsane zamestnancem) do shift_info_dimension */
    public static function extractAttendanceCheckInToShiftInfoDimension($shift_info_id, $now) {
        ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['attendance_check_in' => $now]);
    }

    /* Nazev funkce: extractAttendanceCheckOutToShiftInfoDimension
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, time - cas odchodu zapsany zamestnancem
       Ucel: zapsani odchodu (zapsane zamestnancem) do shift_info_dimension */
    public static function extractAttendanceCheckOutToShiftInfoDimension($shift_info_id, $now) {
        ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['attendance_check_out' => $now]);
    }

    /* Nazev funkce: extractAttendanceCameToShiftFacts
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, employee_id - identifikator zamestnance, company_id - identifikator firmy
       Ucel: indikace toho, ze zamestnanec na smenu prisel */
    public static function extractAttendanceCameToShiftFacts($shift_info_id, $employee_id, $company_id) {
        ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['attendance_came' => 1]);
    }

    /* Nazev funkce: extractAbsenceReasonToShiftFacts
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, employee_id - identifikator zamestnance, company_id - identifikator firmy, absence_reason - konkretni duvod (status)
       Ucel: zapsani statusu dochazky */
    public static function extractAbsenceReasonToShiftFacts($shift_info_id, $employee_id, $company_id, $absence_reason) {
        if ($absence_reason == 4 || $absence_reason == 5) {
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['absence_reason' => $absence_reason, 'attendance_came' => 1]);
        } else {
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['absence_reason' => $absence_reason, 'attendance_came' => 0]);
        }
    }

    /* Nazev funkce: aggregateEmployeeAbsenceTotalHoursAndLateFlag
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, employee_id - identifikator zamestnance, company_id - identifikator firmy, shift_start - zacatek smeny, companyCheckIn - cas zapsani prichodu firmou
       Ucel: transformace celkovych hodin zpozdeni a nastaveni prislusneho priznaku (late_flag) */
    public static function aggregateEmployeeAbsenceTotalHoursAndLateFlag($shift_info_id, $employee_id, $company_id, $shift_start, $companyCheckIn) {
        $shift_start_date = new DateTime($shift_start);
        $late_flag = NULL;
        $pocetHodinAbsence = NULL;
        $company_check_in_date = new DateTime($companyCheckIn);
        if ($company_check_in_date > $shift_start_date) {
            $hodinyRozdil = $company_check_in_date->diff($shift_start_date);
            $pocetHodinAbsence = $pocetHodinAbsence + $hodinyRozdil->h + ($hodinyRozdil->i / 60);
            $late_flag = 1;
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['employee_late_flag' => $late_flag, 'late_total_hours' => $pocetHodinAbsence, 'absence_reason' => 4]);
        } else {
            $late_flag = 0;
            $pocetHodinAbsence = 0;
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['employee_late_flag' => $late_flag, 'late_total_hours' => $pocetHodinAbsence, 'absence_reason' => 5]);
        }
    }

    /* Nazev funkce: aggregateEmployeeTotalWorkedHours
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, employee_id - identifikator zamestnance, company_id - identifikator firmy, companyCheckIn - cas zapsani prichodu firmou, companyCheckOut - cas zapsani odchodu firmou
       Ucel: transformace celkove odpracovanych hodin na smene */
    public static function aggregateEmployeeTotalWorkedHours($shift_info_id, $employee_id, $company_id, $companyCheckIn, $companyCheckOut) {
        $checkinDate = new DateTime($companyCheckIn);
        $checkoutDate = new DateTime($companyCheckOut);
        $pocetHodin = $checkoutDate->diff($checkinDate);
        $total_worked_hours = 0;
        $total_worked_hours = $total_worked_hours + $pocetHodin->h + ($pocetHodin->i/60);
        ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['total_worked_hours' => $total_worked_hours]);
    }

    /* Nazev funkce: aggregateEmployeeInjuryFlag
       Argumenty: shift_info_id - ID smeny v ramci OLAP sekce systemu, employee_id - identifikator zamestnance, company_id - identifikator firmy, opt - 0 pri smazani zraneni a 1 pri vytvoreni zraneni
       Ucel: nastaveni priznaku zraneni v tabulce faktu */
    public static function aggregateEmployeeInjuryFlag($shift_info_id, $employee_id, $company_id, $opt) {
        if($opt == 0){
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['employee_injury_flag' => 0]);
        }else{
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['employee_injury_flag' => 1]);
        }
    }

    /* Nazev funkce: deleteRecordFromEmployeeDimension
       Argumenty: employee_id - identifikator zamestnance
       Ucel: odstraneni zamestnance z OLAP sekce systemu vcetne jeho smen a s tim souvisejici zaznamy v time_dimension */
    public static function deleteRecordFromEmployeeDimension($employee_id) {
        $zamestnanec = EmployeeDimension::find($employee_id);
        if($zamestnanec != NULL){
            $smeny = DB::table('shift_facts')
                ->select('shift_info_dimension.shift_info_id','shift_info_dimension.shift_start','shift_info_dimension.shift_end')
                ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
                ->where(['shift_facts.employee_id' => $employee_id])
                ->get();
            foreach ($smeny as $smena){
                DB::table('shift_info_dimension')
                    ->where(['shift_info_dimension.shift_info_id' => $smena->shift_info_id])
                    ->delete();
                DB::table('time_dimension')
                    ->where(['time_dimension.time_id' => $smena->shift_info_id])
                    ->delete();
            }
            $zamestnanec->delete();
        }
    }

    /* Nazev funkce: deleteRecordFromCompanyDimension
       Argumenty: company_id - identifikator firmy
       Ucel: odstraneni firmy z OLAP sekce systemu vcetne zamestnancu a jejich smen a s tim souvisejici zaznamy v time_dimension */
    public static function deleteRecordFromCompanyDimension($company_id){
        $firma = CompanyDimension::find($company_id);
        if($firma != NULL){
            $zamestnanci = DB::table('shift_facts')
                ->select('employee_dimension.employee_id','employee_dimension.employee_name','employee_dimension.employee_surname')
                ->join('employee_dimension','shift_facts.employee_id','=','employee_dimension.employee_id')
                ->where(['shift_facts.company_id' => $company_id])
                ->get();
            foreach ($zamestnanci as $zamestnanec){
                $smeny = DB::table('shift_facts')
                    ->select('shift_info_dimension.shift_info_id','shift_info_dimension.shift_start','shift_info_dimension.shift_end')
                    ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
                    ->where(['shift_facts.employee_id' => $zamestnanec->employee_id])
                    ->get();
                foreach ($smeny as $smena){
                    DB::table('shift_info_dimension')
                        ->where(['shift_info_dimension.shift_info_id' => $smena->shift_info_id])
                        ->delete();
                    DB::table('time_dimension')
                        ->where(['time_dimension.time_id' => $smena->shift_info_id])
                        ->delete();
                }
                DB::table('employee_dimension')
                    ->where(['employee_dimension.employee_id' => $zamestnanec->employee_id])
                    ->delete();
            }
            $firma->delete();
        }
    }

    /* Nazev funkce: updateEmployeeDimension
       Argumenty: employee_id - identifikator zamestnance, employee_name - krestni jmeno zamestnance, employee_surname - prijmeni zamestnance, employee_position - pracovni pozice zamestnance
       Ucel: aktualizace zaznamu o zamestnanci */
    public static function updateEmployeeDimension($employee_id, $employee_name, $employee_surname, $employee_position){
        EmployeeDimension::where(['employee_id' => $employee_id])->update(['employee_name' => $employee_name, 'employee_surname' => $employee_surname, 'employee_position' => $employee_position]);
    }

    /* Nazev funkce: updateEmployeeScoreOverall
       Argumenty: employee_id - identifikator zamestnance, employee_overall - celkove skore zamestnance
       Ucel: aktualizace celkoveho skore zamestnance */
    public static function updateEmployeeScoreOverall($employee_id, $employee_overall){
        EmployeeDimension::where(['employee_id' => $employee_id])->update(['employee_overall' => $employee_overall]);
        ShiftFacts::where(['employee_id' => $employee_id])->update(['employee_overall' => $employee_overall]);
    }

    /* Nazev funkce: updateShiftInfoDimension
       Argumenty: employee_ids - identifikatory zamestnancu, company_id - identifikator firmy, shift_start_old - puvodni zacatek smeny, shift_end_old - puvodni konec smeny, shift_start - novy zacatek smeny, shift_end - novy konec smeny
       Ucel: aktualizace smen v ramci OLAP sekce systemu. */
    public static function updateShiftInfoDimension($employee_ids, $company_id, $shift_start_old, $shift_end_old ,$shift_start, $shift_end) {
        $shift_day = Carbon::parse($shift_start)->day;
        $shift_month = Carbon::parse($shift_start)->month;
        $shift_quarter = Carbon::parse($shift_start)->quarter;
        $shift_year = Carbon::parse($shift_start)->year;
        for ($i = 0; $i < sizeof($employee_ids); $i++){
           $shift_info_id = self::getShiftInfoId($employee_ids[$i], $company_id, $shift_start_old, $shift_end_old);
           ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['shift_start' => $shift_start, 'shift_end' => $shift_end]);
           TimeDimension::where(['time_id' => $shift_info_id])->update(['day' => $shift_day, 'month' => $shift_month, 'quarter' => $shift_quarter, 'year' => $shift_year]);
        }
    }

    /* Nazev funkce: deleteRecordFromShiftInfoDimension
       Argumenty: employee_ids - identifikatory zamestnancu, company_id - identifikator firmy, shift_start - novy zacatek smeny, shift_end - novy konec smeny
       Ucel: odstraneni smen z OLAP sekce systemu */
    public static function deleteRecordFromShiftInfoDimension($employee_ids, $company_id, $shift_start, $shift_end) {
        $shift_day = Carbon::parse($shift_start)->day;
        $shift_month = Carbon::parse($shift_start)->month;
        $shift_quarter = Carbon::parse($shift_start)->quarter;
        $shift_year = Carbon::parse($shift_start)->year;
        for ($i = 0; $i < sizeof($employee_ids); $i++){
            $shift_info_id = self::getShiftInfoId($employee_ids[$i], $company_id, $shift_start, $shift_end);
            ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->delete();
            TimeDimension::where(['time_id' => $shift_info_id])->delete();
        }
    }

    /* Nazev funkce: updateCompanyDimension
       Argumenty: company_id - identifikator firmy, company_name - nazev firmy, company_city - mesto sidla, company_street - ulice sidla, company_user_name - krestni jmeno zastupce, company_user_surname - prijmeni zastupce
       Ucel: aktualizovani udaju firmy v OLAP sekci systemu */
    public static function updateCompanyDimension($company_id, $company_name, $company_city, $company_street, $company_user_name, $company_user_surname) {
        CompanyDimension::where(['company_id' => $company_id])->update(['company_name' => $company_name, 'company_city' => $company_city, 'company_street' => $company_street, 'company_user_name' => $company_user_name, 'company_user_surname' => $company_user_surname]);
    }

    /* Nazev funkce: aggregateShiftTotalHoursField
       Argumenty: shift - konkretni smena pro vypocet jeji delky
       Ucel: vypocet delky konkretni smeny */
    public static function aggregateShiftTotalHoursField($shift) {
        $shift_start = new DateTime($shift->shift_start);
        $shift_end = new DateTime($shift->shift_end);
        $hodinyRozdil = $shift_end->diff($shift_start);
        return ($hodinyRozdil->h + ($hodinyRozdil->i/60));
    }

    /* Nazev funkce: updateShiftTotalHoursField
       Argumenty: shift - konkretni smena, employee_ids - identifikatory zamestnancu, company_id - identifikator firmy
       Ucel: aktualizace delky smeny */
    public static function updateShiftTotalHoursField($shift_start, $shift_end, $employee_ids, $company_id) {
        $shift_start_calc = new DateTime($shift_start);
        $shift_end_calc = new DateTime($shift_end);
        $hodinyRozdil = $shift_end_calc->diff($shift_start_calc);
        $celkove = $hodinyRozdil->h + ($hodinyRozdil->i/60);

        for ($i = 0; $i < sizeof($employee_ids); $i++){

            $shift_info_id = self::getShiftInfoId($employee_ids[$i], $company_id, $shift_start, $shift_end);
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_ids[$i], 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['shift_total_hours' => $celkove]);
        }
    }

    /* Nazev funkce: extractDataToShiftFact
       Argumenty: shift - konkretni smena, employee - konkretni zamestnanec, shift_info_id - id smeny v OLAP sekci systemu, time_id - id zaznamu v time_dimension, employee_id - identifikator zamestnance, company_id - identifikator firmy
       Ucel: extrakce dat do tabulky faktů */
    public static function extractDataToShiftFact($shift, $employee, $shift_info_id, $time_id, $employee_id, $company_id){
        $total_hours = self::aggregateShiftTotalHoursField($shift);
        ShiftFacts::firstOrCreate(['company_id' => $company_id, 'time_id' => $time_id, 'employee_id' => $employee_id, 'shift_info_id' => $shift_info_id],['company_id' => $company_id, 'time_id' => $time_id, 'employee_id' => $employee_id, 'shift_info_id' => $shift_info_id
            ,'shift_total_hours' => $total_hours, 'late_total_hours' => NULL, 'employee_injury_flag' => NULL, 'employee_overall' => $employee->employee_overall, 'employee_late_flag' => NULL, 'attendance_came' => NULL, 'absence_reason' => NULL]);
    }

    /* Nazev funkce: deleteCancelledPreviouslyAssignedShift
       Argumenty: employee_id - identifikator zamestnance, shift_starts_arr - seznam zacatku smen, shift_ends_arr - seznam koncu smen
       Ucel: Aktualizace smen tak, aby byly vzdy aktualni (napriklad pri zmene smeny se stara odstrani, pokud uz v systemu dale nema byt), tato metoda se pouziva v seznamu zamestnancu */
    public static function deleteCancelledPreviouslyAssignedShift($employee_id, $shift_starts_arr, $shift_ends_arr){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_facts')
            ->select('shift_info_dimension.shift_info_id','shift_info_dimension.shift_start','shift_info_dimension.shift_end')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->whereNotIn('shift_info_dimension.shift_start',$shift_starts_arr)
            ->whereNotIn('shift_info_dimension.shift_end',$shift_ends_arr)
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where('shift_info_dimension.shift_start', '>=', Carbon::now())
            ->get();

        DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->whereNotIn('shift_info_dimension.shift_start',$shift_starts_arr)
            ->whereNotIn('shift_info_dimension.shift_end',$shift_ends_arr)
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where('shift_info_dimension.shift_start', '>=', Carbon::now())
            ->delete();

        foreach ($smeny as $smena){
            DB::table('shift_info_dimension')
                ->where(['shift_info_dimension.shift_info_id' => $smena->shift_info_id])
                ->where('shift_info_dimension.shift_start', '>=', Carbon::now())
                ->delete();

            DB::table('time_dimension')
                ->where(['time_dimension.time_id' => $smena->shift_info_id])
                ->delete();
        }
    }

    /* Nazev funkce: deleteAllCancelledPreviouslyAssignedShift
       Argumenty: employee_id - identifikator zamestnance
       Ucel: Smazani vsech smen zamestnance v seznamu zamestnancu z OLAP sekce systemu (pouzije se pokud zbyva pouze jedna prirazena smena, ktera se odstrani) */
    public static function deleteAllCancelledPreviouslyAssignedShift($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_facts')
            ->select('shift_info_dimension.shift_info_id','shift_info_dimension.shift_start','shift_info_dimension.shift_end')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where('shift_info_dimension.shift_start', '>=',  Carbon::now())
            ->get();

        DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where('shift_info_dimension.shift_start', '>=',  Carbon::now())
            ->delete();

        foreach ($smeny as $smena){
            DB::table('shift_info_dimension')
                ->where(['shift_info_dimension.shift_info_id' => $smena->shift_info_id])
                ->where('shift_info_dimension.shift_start', '>=', Carbon::now())
                ->delete();

            DB::table('time_dimension')
                ->where(['time_dimension.time_id' => $smena->shift_info_id])
                ->delete();
        }
    }

    /* Nazev funkce: deleteCancelledPreviouslyAssignedEmployee
       Argumenty: shift_start - zacatek smeny, shift_end - konec smeny, employee_ids_arr - identifikatory zamestnancu
       Ucel: Smazani zamestnancu ze smeny v seznamu smen z OLAP sekce systemu */
    public static function deleteCancelledPreviouslyAssignedEmployee($shift_start, $shift_end, $employee_ids_arr){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_facts')
            ->select('shift_info_dimension.shift_info_id','shift_info_dimension.shift_start','shift_info_dimension.shift_end')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->whereNotIn('shift_facts.employee_id',$employee_ids_arr)
            ->where(['shift_info_dimension.shift_start' => $shift_start])
            ->where(['shift_info_dimension.shift_end' => $shift_end])
            ->get();

        DB::table('shift_facts')
            ->select('shift_info_dimension.shift_info_id','shift_info_dimension.shift_start','shift_info_dimension.shift_end')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->whereNotIn('shift_facts.employee_id',$employee_ids_arr)
            ->where(['shift_info_dimension.shift_start' => $shift_start])
            ->where(['shift_info_dimension.shift_end' => $shift_end])
            ->delete();

        foreach ($smeny as $smena){
            DB::table('shift_info_dimension')
                ->where(['shift_info_dimension.shift_info_id' => $smena->shift_info_id])
                ->delete();

            DB::table('time_dimension')
                ->where(['time_dimension.time_id' => $smena->shift_info_id])
                ->delete();
        }
    }

    /* Nazev funkce: deleteAllCancelledPreviouslyAssignedEmployee
       Argumenty: shift_start - zacatek smeny, shift_end - konec smeny
       Ucel: Smazani vsech zamestnancu ze smeny v seznamu smen z OLAP sekce systemu */
    public static function deleteAllCancelledPreviouslyAssignedEmployee($shift_start, $shift_end){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_facts')
            ->select('shift_info_dimension.shift_info_id','shift_info_dimension.shift_start','shift_info_dimension.shift_end')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_info_dimension.shift_start' => $shift_start])
            ->where(['shift_info_dimension.shift_end' => $shift_end])
            ->get();

        DB::table('shift_facts')
            ->select('shift_info_dimension.shift_info_id','shift_info_dimension.shift_start','shift_info_dimension.shift_end')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_info_dimension.shift_start' => $shift_start])
            ->where(['shift_info_dimension.shift_end' => $shift_end])
            ->delete();

        foreach ($smeny as $smena){
            DB::table('shift_info_dimension')
                ->where(['shift_info_dimension.shift_info_id' => $smena->shift_info_id])
                ->delete();

            DB::table('time_dimension')
                ->where(['time_dimension.time_id' => $smena->shift_info_id])
                ->delete();
        }
    }

}
