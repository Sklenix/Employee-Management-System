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
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->sum('shift_total_hours');
    }

    public static function getTotalShiftsHoursByMonths($company_id){

    }

    public static function getTotalEmployeeShiftsHoursByMonths($employee_id){

    }


    public static function getTotalShiftsWorkedHours($company_id){

    }

    public static function getTotalEmployeeShiftsWorkedHours($employee_id){

    }

    public static function getTotalWorkedShiftsHoursByMonths($company_id){

    }

    public static function getTotalEmployeeLateFlagsCount($employee_id){

    }

    public static function getTotalLateFlagsCount($company_id){

    }

    public static function getTotalEmployeeLateFlagsCountByMonths($company_id){

    }

    public static function getTotalLateFlagsThisMonth($company_id){

    }



}
