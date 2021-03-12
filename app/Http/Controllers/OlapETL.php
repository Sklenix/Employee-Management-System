<?php

namespace App\Http\Controllers;

use App\Models\CompanyDimension;
use App\Models\EmployeeDimension;
use App\Models\ShiftFacts;
use App\Models\ShiftInfoDimension;
use App\Models\TimeDimension;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OlapETL extends Controller
{
    public static function extractDataToShiftInfoDimension($shift){
        $new_shift_info_dimension = ShiftInfoDimension::create(['shift_start' => $shift->shift_start, 'shift_end' => $shift->shift_end, 'attendance_came' => NULL, 'attendance_check_in' => NULL, 'attendance_check_out' => NULL, 'attendance_check_in_company' => NULL, 'attendance_check_out_company' => NULL]);
        return $new_shift_info_dimension->id;
    }

    public static function extractDataToTimeDimension($shift_info_id, $shift){
        $new_time_dimension = TimeDimension::create(['time_id' => $shift_info_id, 'day' => Carbon::parse($shift->shift_start)->day, 'month' => Carbon::parse($shift->shift_start)->month, 'quarter' => Carbon::parse($shift->shift_start)->quarter, 'year' => Carbon::parse($shift->shift_start)->year]);
        return $new_time_dimension->time_id;
    }

    public static function extractDataToEmployeeDimension($employee){
        $new_employee_dimension = EmployeeDimension::firstOrCreate(['employee_id' => $employee->employee_id],['employee_id' => $employee->employee_id, 'employee_name' => $employee->employee_name, 'employee_surname' => $employee->employee_surname, 'employee_position' => $employee->employee_position, 'employee_overall' => $employee->employee_overall]);
        return $new_employee_dimension->employee_id;
    }

    public static function extractDataToCompanyDimension($user){
        $new_company_dimension = CompanyDimension::firstOrCreate(['company_id' => $user->company_id],['company_id' => $user->company_id, 'company_name' => $user->company_name, 'company_city' => $user->company_city, 'company_street' => $user->company_street, 'company_user_name' => $user->company_user_name, 'company_user_surname' => $user->company_user_surname]);
        return $new_company_dimension->company_id;
    }

    public static function aggregateShiftTotalHoursField($shift){
        $shift_start = new DateTime($shift->shift_start);
        $shift_end = new DateTime($shift->shift_end);
        $hodinyRozdil = $shift_end->diff($shift_start);
        return ($hodinyRozdil->h + ($hodinyRozdil->i/60));
    }

    public static function extractDataToShiftFact($shift, $employee, $shift_info_id, $time_id, $employee_id, $company_id){
        $total_hours = self::aggregateShiftTotalHoursField($shift);
        ShiftFacts::firstOrCreate(['company_id' => $company_id, 'time_id' => $time_id, 'employee_id' => $employee_id, 'shift_info_id' => $shift_info_id],['company_id' => $company_id, 'time_id' => $time_id, 'employee_id' => $employee_id, 'shift_info_id' => $shift_info_id
            ,'shift_total_hours' => $total_hours, 'absence_total_hours' => NULL, 'employee_injury_flag' => NULL, 'employee_overall' => $employee->employee_overall, 'employee_late_flag' => NULL, 'absence_reason' => NULL]);
    }

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

}

