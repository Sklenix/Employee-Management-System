<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OlapAnalyzator extends Controller
{
    public static function getCountOfShiftFacts($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->count();
    }

    public static function getCountOfEmployeeShiftFacts($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->count();
    }

    public static function getCountUpcomingShiftFacts($company_id){
        date_default_timezone_set('Europe/Prague');
         return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where('shift_info_dimension.shift_start', '>=',  Carbon::now())
            ->count();
    }

    public static function getCountHistoricalShiftFacts($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where('shift_info_dimension.shift_start', '<',  Carbon::now())
            ->count();
    }

    public static function getCountOfShiftFactsByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
       $smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function getCountOfEmployeeShiftFactsByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function getTotalShiftsHours($company_id){
        $shifts_hours = DB::table('shift_facts')
                            ->where(['shift_facts.company_id' => $company_id])
                            ->sum('shift_total_hours');
        if($shifts_hours == NULL){
            return 0;
        }else{
            return $shifts_hours;
        }
    }

    public static function getTotalEmployeeShiftsHours($employee_id){
        $shifts_hours = DB::table('shift_facts')
                            ->where(['shift_facts.employee_id' => $employee_id])
                            ->sum('shift_total_hours');
        if($shifts_hours == NULL){
            return 0;
        }else{
            return $shifts_hours;
        }
    }

    public static function getTotalShiftsHoursByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(shift_total_hours,0)) as sum_shift_total_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_total_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_hodiny[$index];
        }
        return $data_shifts;
    }

    public static function getTotalEmployeeShiftsHoursByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(shift_total_hours,0)) as sum_shift_total_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_total_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_hodiny[$index];
        }
        return $data_shifts;
    }

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

    public static function getTotalWorkedShiftsHoursByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_odpracovane_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(total_worked_hours,0)) as sum_shift_total_worked_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_total_worked_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = round($smeny_odpracovane_hodiny[$index],2);
        }
        return $data_shifts;
    }

    public static function getTotalEmployeeShiftsWorkedHoursByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_odpracovane_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(total_worked_hours,0)) as sum_shift_total_worked_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_total_worked_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_odpracovane_hodiny[$index];
        }
        return $data_shifts;
    }

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

    public static function getTotalEmployeeLateShiftHours($employee_id){
        $late_hours = DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->sum('late_total_hours');
        if($late_hours == NULL){
            return 0;
        }else{
            return $late_hours;
        }
    }

    public static function getTotalLateShiftsHoursByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_zpozdeni_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(late_total_hours,0)) as sum_shift_late_total_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_late_total_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_zpozdeni_hodiny[$index];
        }
        for ($i = 0; $i < sizeof($data_shifts); $i++){
            $data_shifts[$i] = round($data_shifts[$i],3);
        }
        return $data_shifts;
    }

    public static function getTotalEmployeeLateShiftsHoursByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_zpozdeni_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(late_total_hours,0)) as sum_shift_late_total_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_late_total_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_zpozdeni_hodiny[$index];
        }
        return $data_shifts;
    }

    public static function getTotalLateFlagsCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['employee_late_flag' => 1])
            ->count('employee_late_flag');
    }

    public static function getTotalEmployeeLateFlagsCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['employee_late_flag' => 1])
            ->count('employee_late_flag');
    }

    public static function getTotalLateFlagsCountByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_late_flagy = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(employee_late_flag) as count_employee_late_flags"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_employee_late_flags');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_late_flagy[$index];
        }
        return $data_shifts;
    }

    public static function getTotalEmployeeLateFlagsCountByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_late_flagy = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(employee_late_flag) as count_employee_late_flags"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_employee_late_flags');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_late_flagy[$index];
        }
        return $data_shifts;
    }

    public static function getTotalInjuryFlagsCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['employee_injury_flag' => 1])
            ->count('employee_injury_flag');
    }

    public static function getTotalEmployeeInjuryFlagsCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['employee_injury_flag' => 1])
            ->count('employee_injury_flag');
    }

    public static function getTotalInjuryFlagsCountByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_injury_flagy = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(employee_injury_flag) as count_employee_injury_flags"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['shift_facts.employee_injury_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_employee_injury_flags');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->where(['shift_facts.employee_injury_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_injury_flagy[$index];
        }
        return $data_shifts;
    }

    public static function getTotalEmployeeInjuryFlagsCountByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny_injury_flagy = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(employee_injury_flag) as count_employee_injury_flags"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['shift_facts.employee_injury_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_employee_injury_flags');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->where(['shift_facts.employee_injury_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_injury_flagy[$index];
        }
        return $data_shifts;
    }

    public static function getTotalAbsenceCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereIn('shift_facts.absence_reason' , [1,2,3])
            ->count('shift_facts.absence_reason');
    }

    public static function getTotalEmployeeAbsenceCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereIn('shift_facts.absence_reason' , [1,2,3])
            ->count('shift_facts.absence_reason');
    }

    public static function getTotalNonAbsenceCount($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereIn('shift_facts.absence_reason' , [4,5])
            ->count('shift_facts.absence_reason');
    }

    public static function getTotalEmployeeNonAbsenceCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereIn('shift_facts.absence_reason' , [4,5])
            ->count('shift_facts.absence_reason');
    }

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

    public static function getAverageEmployeesScoresByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci_skore = DB::table('shift_info_dimension')
            ->select(DB::raw("IFNULL(SUM(IFNULL(employee_overall,0)) / COUNT(employee_overall),0) as avg_employee_overall"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('avg_employee_overall');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_skore = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_skore[$month_shift - 1] = round($zamestnanci_skore[$index], 2);
        }
        return $data_skore;
    }

    public static function getAverageEmployeeScoreByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci_skore = DB::table('shift_info_dimension')
            ->select(DB::raw("IFNULL(SUM(IFNULL(employee_overall,0)) / COUNT(employee_overall),0) as avg_employee_overall"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('avg_employee_overall');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_skore = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_skore[$month_shift - 1] = $zamestnanci_skore[$index];
        }
        return $data_skore;
    }
}
