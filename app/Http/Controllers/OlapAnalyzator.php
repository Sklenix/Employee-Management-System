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

    public static function getUpcomingShiftFacts($company_id){
        date_default_timezone_set('Europe/Prague');
         return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where('shift_info_dimension.shift_start', '>=',  Carbon::now())
            ->count();
    }

    public static function getHistoricalShiftFacts($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where('shift_info_dimension.shift_start', '<',  Carbon::now())
            ->count();
    }

    public static function getTotalShiftWorkedHours($company_id){
        return DB::table('shift_facts')
            ->where(['shift_facts.company_id' => $company_id])
            ->sum('shift_total_hours');
    }

    public static function getTotalWorkedHours($company_id){

    }

    public static function getTotalEmployeeWorkedHours($employee_id){

    }

    public static function getTotalWorkedHoursThisMonth($company_id){

    }

    public static function getTotalWorkedHoursByMonths($company_id){

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
