<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OlapAnalyzator extends Controller {
    /* Nazev souboru:  OlapAnalyzator.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi k ziskavani dat z OLAP sekce systemu. */

    /* Nazev funkce: getCountOfShiftFacts
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu prirazenych smen firmy */
    public static function getCountOfShiftFacts($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->count();
    }

    /* Nazev funkce: getCountOfEmployeeShiftFacts
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu prirazenych smen konkretniho zamestnance firmy */
    public static function getCountOfEmployeeShiftFacts($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->count();
    }

    /* Nazev funkce: getCountUpcomingShiftFacts
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu budoucich prirazenych smen firmy */
    public static function getCountUpcomingShiftFacts($company_id){
        date_default_timezone_set('Europe/Prague');
         return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where('shift_info_dimension.shift_start', '>=',  Carbon::now())
            ->count();
    }

    /* Nazev funkce: getCountHistoricalShiftFacts
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu historicky prirazenych smen firmy */
    public static function getCountHistoricalShiftFacts($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where('shift_info_dimension.shift_start', '<',  Carbon::now())
            ->count();
    }

    /* Nazev funkce: getCountOfShiftFactsByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu prirazenych smen dle mesicu */
    public static function getCountOfShiftFactsByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        /* Ziskani poctu smen dle mesicu */
       $smeny = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(*) as count_shifts')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        /* Ziskani tech mesicu, kde jsou nejake smeny */
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        /* Tento usek kodu se stara o naplneni poctu smen k jednotlivym mesicum */
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts - 1] = $smeny[$i]->count_shifts;
        }
        /* Vraceni poctu prirazenych smen dle mesicu */
        return $statistikaSmen;
    }

    /* Nazev funkce: getCountOfEmployeeShiftFactsByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu prirazenych smen zamestnance dle mesicu */
    public static function getCountOfEmployeeShiftFactsByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(*) as count_shifts')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts - 1] = $smeny[$i]->count_shifts;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalShiftsHours
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu hodin smen firmy */
    public static function getTotalShiftsHours($company_id){
        $shifts_hours = DB::table('shift_facts')
                            ->where(['shift_facts.company_id' => $company_id])
                            ->sum('shift_total_hours');
        if($shifts_hours == NULL){
            return 0;
        }else{
            return round($shifts_hours, 2);
        }
    }

    /* Nazev funkce: getTotalEmployeeShiftsHours
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu hodin smen zamestnance */
    public static function getTotalEmployeeShiftsHours($employee_id){
        $shifts_hours = DB::table('shift_facts')
                            ->where(['shift_facts.employee_id' => $employee_id])
                            ->sum('shift_total_hours');
        if($shifts_hours == NULL){
            return 0;
        }else{
            return round($shifts_hours, 2);
        }
    }

    /* Nazev funkce: getTotalShiftsHoursByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu hodin smen dle mesicu */
    public static function getTotalShiftsHoursByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(shift_total_hours,0)) as sum_shift_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shift_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shift_total_hours - 1] = round($smeny_hodiny[$i]->sum_shift_total_hours, 2);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalEmployeeShiftsHoursByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu hodin smen zamestnance dle mesicu */
    public static function getTotalEmployeeShiftsHoursByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(shift_total_hours,0)) as sum_shift_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shift_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shift_total_hours - 1] = round($smeny_hodiny[$i]->sum_shift_total_hours, 2);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalShiftsWorkedHours
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu odpracovanych hodin v ramci smen firmy */
    public static function getTotalShiftsWorkedHours($company_id){
        $shifts_hours = DB::table('shift_facts')
                            ->where(['shift_facts.company_id' => $company_id])
                            ->sum('total_worked_hours');
        if($shifts_hours == NULL){
            return 0;
        }else{
            return round($shifts_hours, 2);
        }
    }

    /* Nazev funkce: getTotalEmployeeShiftsWorkedHours
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu odpracovanych hodin v ramci smen zamestnance */
    public static function getTotalEmployeeShiftsWorkedHours($employee_id){
        $shifts_hours = DB::table('shift_facts')
                            ->where(['shift_facts.employee_id' => $employee_id])
                            ->sum('total_worked_hours');
        if($shifts_hours == NULL){
            return 0;
        }else{
            return $shifts_hours;
        }
    }

    /* Nazev funkce: getTotalWorkedShiftsHoursByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu odpracovanych hodin v ramci smen firmy dle mesicu */
    public static function getTotalWorkedShiftsHoursByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_odpracovane_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(total_worked_hours,0)) as sum_shift_total_worked_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shift_total_worked_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shift_total_worked_hours - 1] = round($smeny_odpracovane_hodiny[$i]->sum_shift_total_worked_hours, 2);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalEmployeeShiftsWorkedHoursByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu odpracovanych hodin v ramci smen zamestnance dle mesicu */
    public static function getTotalEmployeeShiftsWorkedHoursByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_odpracovane_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(total_worked_hours,0)) as sum_shift_total_worked_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shift_total_worked_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shift_total_worked_hours - 1] = round($smeny_odpracovane_hodiny[$i]->sum_shift_total_worked_hours, 2);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalLateShiftHours
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu hodin zpozdeni firmy */
    public static function getTotalLateShiftHours($company_id){
        $late_hours = DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->sum('late_total_hours');
        if($late_hours == NULL){
            return 0;
        }else{
            return round($late_hours, 3);
        }
    }

    /* Nazev funkce: getTotalEmployeeLateShiftHours
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu hodin zpozdeni zamestnance */
    public static function getTotalEmployeeLateShiftHours($employee_id){
        $late_hours = DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->sum('late_total_hours');
        if($late_hours == NULL){
            return 0;
        }else{
            return round($late_hours, 3);
        }
    }

    /* Nazev funkce: getTotalLateShiftsHoursByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu hodin zpozdeni firmy dle mesicu */
    public static function getTotalLateShiftsHoursByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_zpozdeni_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(late_total_hours,0)) as sum_shift_late_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shift_late_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shift_late_total_hours - 1] = round($smeny_zpozdeni_hodiny[$i]->sum_shift_late_total_hours, 2);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalEmployeeLateShiftsHoursByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu hodin zpozdeni zamestnance dle mesicu */
    public static function getTotalEmployeeLateShiftsHoursByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_zpozdeni_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(late_total_hours,0)) as sum_shift_late_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shift_late_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shift_late_total_hours - 1] = round($smeny_zpozdeni_hodiny[$i]->sum_shift_late_total_hours, 2);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalLateFlagsCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu zpozdeni firmy */
    public static function getTotalLateFlagsCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['employee_late_flag' => 1])
            ->count('employee_late_flag');
    }

    /* Nazev funkce: getTotalEmployeeLateFlagsCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu zpozdeni zamestnance */
    public static function getTotalEmployeeLateFlagsCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['employee_late_flag' => 1])
            ->count('employee_late_flag');
    }

    /* Nazev funkce: getTotalLateFlagsCountByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu zpozdeni firmy dle mesicu */
    public static function getTotalLateFlagsCountByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_late_flagy = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(employee_late_flag) as count_employee_late_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_employee_late_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_employee_late_flags - 1] = $smeny_late_flagy[$i]->count_employee_late_flags;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalEmployeeLateFlagsCountByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu zpozdeni zamestnance dle mesicu */
    public static function getTotalEmployeeLateFlagsCountByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_late_flagy = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(employee_late_flag) as count_employee_late_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_employee_late_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_employee_late_flags - 1] = $smeny_late_flagy[$i]->count_employee_late_flags;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalInjuryFlagsCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu zraneni firmy */
    public static function getTotalInjuryFlagsCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['employee_injury_flag' => 1])
            ->count('employee_injury_flag');
    }

    /* Nazev funkce: getTotalEmployeeInjuryFlagsCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu zraneni zamestnance */
    public static function getTotalEmployeeInjuryFlagsCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['employee_injury_flag' => 1])
            ->count('employee_injury_flag');
    }

    /* Nazev funkce: getTotalInjuryFlagsCountByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu zraneni firmy dle mesicu */
    public static function getTotalInjuryFlagsCountByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_injury_flagy = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(employee_injury_flag) as count_employee_injury_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['shift_facts.employee_injury_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_employee_injury_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->where(['shift_facts.employee_injury_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_employee_injury_flags - 1] = $smeny_injury_flagy[$i]->count_employee_injury_flags;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalEmployeeInjuryFlagsCountByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu zraneni zamestnance dle mesicu */
    public static function getTotalEmployeeInjuryFlagsCountByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_injury_flagy = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(employee_injury_flag) as count_employee_injury_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['shift_facts.employee_injury_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_employee_injury_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->where(['shift_facts.employee_injury_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_employee_injury_flags - 1] = $smeny_injury_flagy[$i]->count_employee_injury_flags;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getTotalAbsenceCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu absenci v ramci smen firmy */
    public static function getTotalAbsenceCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereIn('shift_facts.absence_reason' , [1,2,3])
            ->count('shift_facts.absence_reason');
    }

    /* Nazev funkce: getTotalEmployeeAbsenceCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu absenci v ramci smen zamestnance */
    public static function getTotalEmployeeAbsenceCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereIn('shift_facts.absence_reason' , [1,2,3])
            ->count('shift_facts.absence_reason');
    }

    /* Nazev funkce: getTotalNonAbsenceCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani celkoveho poctu neabsenci v ramci smen firmy */
    public static function getTotalNonAbsenceCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereIn('shift_facts.absence_reason' , [4,5])
            ->count('shift_facts.absence_reason');
    }

    /* Nazev funkce: getTotalEmployeeNonAbsenceCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani celkoveho poctu neabsenci v ramci smen zamestnance */
    public static function getTotalEmployeeNonAbsenceCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereIn('shift_facts.absence_reason' , [4,5])
            ->count('shift_facts.absence_reason');
    }

    /* Nazev funkce: getAverageEmployeesScores
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani prumerneho skore zamestnancu firmy */
    public static function getAverageEmployeesScores($company_id){
        $skore = DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->avg('employee_overall');
        if($skore == NULL){
            return 0;
        }else{
            return round($skore, 2);
        }
    }

    /* Nazev funkce: getAverageEmployeeScore
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani prumerneho skore zamestnance */
    public static function getAverageEmployeeScore($employee_id){
       $skore = DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->avg('employee_overall');
       if($skore == NULL){
           return 0;
       }else{
           return round($skore, 2);
       }
    }

    /* Nazev funkce: getAverageEmployeesScoresByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani prumerneho skore zamestnancu firmy dle mesicu */
    public static function getAverageEmployeesScoresByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci_skore = DB::table('shift_info_dimension')
            ->selectRaw('IFNULL(SUM(IFNULL(employee_overall,0)) / COUNT(employee_overall),0) as avg_employee_overall')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_employee_overall')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSkore = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSkore[$mesice_smeny[$i]->month_employee_overall - 1] = round($zamestnanci_skore[$i]->avg_employee_overall, 2);
        }
        return $statistikaSkore;
    }

    /* Nazev funkce: getAverageEmployeeScoreByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani prumerneho skore zamestnance dle mesicu */
    public static function getAverageEmployeeScoreByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci_skore = DB::table('shift_info_dimension')
            ->selectRaw('IFNULL(SUM(IFNULL(employee_overall,0)) / COUNT(employee_overall),0) as avg_employee_overall')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_employee_overall')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSkore = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSkore[$mesice_smeny[$i]->month_employee_overall - 1] = round($zamestnanci_skore[$i]->avg_employee_overall, 2);
        }
        return $statistikaSkore;
    }

    /* Nazev funkce: getShiftTotalWorkedHoursByQuarter
       Argumenty: rok - zvoleny rok, quarter - zvolene ctvrtleti
       Ucel: Zobrazeni odpracovanych hodin za konkretni ctvrtleti konkretniho roku */
    public static function getShiftTotalWorkedHoursByQuarter($rok, $quarter){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->join('time_dimension','shift_facts.time_id','=','time_dimension.time_id')
            ->where(['time_dimension.quarter' => $quarter])
            ->where(['time_dimension.year' => $rok])
            ->sum('shift_facts.total_worked_hours');
    }

    /* Nazev funkce: getShiftTotalWorkedHoursInMonth
       Argumenty: rok - zvoleny rok, month - zvoleny mesic
       Ucel: Zobrazeni odpracovanych hodin za konkretni mesic konkretniho roku */
    public static function getShiftTotalWorkedHoursInMonth($rok, $month){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->join('time_dimension','shift_facts.time_id','=','time_dimension.time_id')
            ->where(['time_dimension.month' => $month])
            ->where(['time_dimension.year' => $rok])
            ->sum('shift_facts.total_worked_hours');
    }

    /* Nazev funkce: getShiftTotalLateHoursByQuarter
      Argumenty: rok - zvoleny rok, quarter - zvolene ctvrtleti
      Ucel: Zobrazeni celkovych hodin zpozdeni za konkretni ctvrtleti konkretniho roku */
    public static function getShiftTotalLateHoursByQuarter($rok, $quarter){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->join('time_dimension','shift_facts.time_id','=','time_dimension.time_id')
            ->where(['time_dimension.quarter' => $quarter])
            ->where(['time_dimension.year' => $rok])
            ->sum('shift_facts.late_total_hours');
    }

    /* Nazev funkce: getShiftTotalLateHoursInMonth
       Argumenty: rok - zvoleny rok, month - zvoleny mesic
       Ucel: Zobrazeni celkovych hodin zpozdeni za konkretni mesic konkretniho roku */
    public static function getShiftTotalLateHoursInMonth($rok, $month){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->join('time_dimension','shift_facts.time_id','=','time_dimension.time_id')
            ->where(['time_dimension.month' => $month])
            ->where(['time_dimension.year' => $rok])
            ->sum('shift_facts.late_total_hours');
    }

    /* Nazev funkce: getShiftTotalLateHoursInYear
      Argumenty: rok - zvoleny rok
      Ucel: Zobrazeni celkovych hodin zpozdeni za konkretni rok */
    public static function getShiftTotalLateHoursInYear($rok){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->join('time_dimension','shift_facts.time_id','=','time_dimension.time_id')
            ->where(['time_dimension.year' => $rok])
            ->sum('shift_facts.late_total_hours');
    }

    /* Nazev funkce: getShiftTotalWorkedHoursInYear
       Argumenty: rok - zvoleny rok, month - zvoleny mesic
       Ucel: Zobrazeni odpracovanych hodin za konkretni rok */
    public static function getShiftTotalWorkedHoursInYear($rok){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->join('time_dimension','shift_facts.time_id','=','time_dimension.time_id')
            ->where(['time_dimension.year' => $rok])
            ->sum('shift_facts.total_worked_hours');
    }

}
