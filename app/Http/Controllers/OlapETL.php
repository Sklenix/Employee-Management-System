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
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class OlapETL extends Controller
{
    public static function extractDataToShiftInfoDimension($shift){
        $new_shift_info_dimension = ShiftInfoDimension::create(['shift_start' => $shift->shift_start, 'shift_end' => $shift->shift_end, 'attendance_came' => NULL, 'attendance_check_in' => NULL, 'attendance_check_out' => NULL, 'attendance_check_in_company' => NULL, 'attendance_check_out_company' => NULL]);
        return $new_shift_info_dimension->shift_info_id;
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

    public static function getShiftInfoId($employee_id, $shift_start, $shift_end){
        $smena = DB::table('shift_facts')
            ->select('shift_info_dimension.shift_info_id')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where(['shift_info_dimension.shift_start' => $shift_start])
            ->where(['shift_info_dimension.shift_end' => $shift_end])
            ->where(['shift_facts.employee_id' => $employee_id])
            ->first();
        return $smena->shift_info_id;
    }

    public static function extractAttendanceCheckInCompanyToShiftInfoDimension($shift_info_id, $time){
        ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['attendance_check_in_company' => $time, 'attendance_came' => 1]);
    }

    public static function extractAttendanceCheckOutCompanyToShiftInfoDimension($shift_info_id, $time){
        ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['attendance_check_out_company' => $time, 'attendance_came' => 1]);
    }

    public static function extractAttendanceCheckInToShiftInfoDimension($shift_info_id, $now){
        ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['attendance_check_in' => $now, 'attendance_came' => 1]);
    }

    public static function extractAttendanceCheckOutToShiftInfoDimension($shift_info_id, $now){
        ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['attendance_check_out' => $now, 'attendance_came' => 1]);
    }

    public static function extractAbsenceReasonToShiftInfoDimension($shift_info_id, $absence_reason){
        if($absence_reason == 4 || $absence_reason == 5){
            ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['absence_reason' => $absence_reason, 'attendance_came' => 1]);
        }else{
            ShiftInfoDimension::where(['shift_info_id' => $shift_info_id])->update(['absence_reason' => $absence_reason, 'attendance_came' => 0]);
        }
    }

    public static function extractAbsenceReasonToShiftFacts($shift_info_id, $employee_id, $company_id, $absence_reason){
        ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['absence_reason' => $absence_reason]);
    }

    public static function aggregateEmployeeAbsenceTotalHoursAndLateFlag($shift_info_id, $employee_id, $company_id, $shift_start, $companyCheckIn, $checkIn){
        $shift_start_date = new DateTime($shift_start);
        $company_check_in_date = new DateTime($companyCheckIn);
        $check_in_date = new DateTime($checkIn);
        $late_flag = NULL;
        $pocetHodinAbsence = NULL;
        if($companyCheckIn != NULL){
            if($company_check_in_date > $shift_start_date){
                $hodinyRozdil = $company_check_in_date->diff($shift_start_date);
                $pocetHodinAbsence = $pocetHodinAbsence + $hodinyRozdil->h + ($hodinyRozdil->i/60);
                $late_flag = 1;
            }else{
                $late_flag = 0;
                $pocetHodinAbsence = 0;
            }
        }else{
            if($checkIn != NULL){
                if($check_in_date > $shift_start_date){
                    $hodinyRozdil = $check_in_date->diff($shift_start_date);
                    $pocetHodinAbsence = $pocetHodinAbsence + $hodinyRozdil->h + ($hodinyRozdil->i/60);
                    $late_flag = 1;
                }else{
                    $late_flag = 0;
                    $pocetHodinAbsence = 0;
                }
            }
        }
        ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['employee_late_flag' => $late_flag, 'late_total_hours' => $pocetHodinAbsence]);
    }

    public static function aggregateEmployeeTotalWorkedHours($shift_info_id, $employee_id, $company_id, $companyCheckIn, $companyCheckOut, $checkIn, $checkOut){
        if($companyCheckIn == NULL || $companyCheckOut == NULL){
            if($checkIn == NULL || $checkOut == NULL){
                return NULL;
            }else{
                $checkinDate = new DateTime($checkIn);
                $checkoutDate = new DateTime($checkOut);
                $pocetHodin = $checkoutDate->diff($checkinDate);
                $total_worked_hours = 0;
                $total_worked_hours = $total_worked_hours + $pocetHodin->h + ($pocetHodin->i/60);
                ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['total_worked_hours' => $total_worked_hours]);
            }
        }else{
            $checkinDate = new DateTime($companyCheckIn);
            $checkoutDate = new DateTime($companyCheckOut);
            $pocetHodin = $checkoutDate->diff($checkinDate);
            $total_worked_hours = 0;
            $total_worked_hours = $total_worked_hours + $pocetHodin->h + ($pocetHodin->i/60);
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['total_worked_hours' => $total_worked_hours]);
        }
    }

    public static function aggregateEmployeeInjuryFlag($shift_info_id, $employee_id, $company_id, $opt){
        if($opt == 0){
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['employee_injury_flag' => 0]);
        }else{
            ShiftFacts::where(['shift_info_id' => $shift_info_id, 'employee_id' => $employee_id, 'company_id' => $company_id, 'time_id' => $shift_info_id])->update(['employee_injury_flag' => 1]);
        }
    }

    public static function deleteRecordFromEmployeeDimension($employee_id){
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
        EmployeeDimension::findOrFail($employee_id)->delete();
    }

    public static function deleteRecordFromCompanyDimension($company_id){
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
        CompanyDimension::findOrFail($company_id)->delete();
    }

    public static function updateEmployeeDimension($employee_id, $employee_name, $employee_surname, $employee_position){
        EmployeeDimension::where(['employee_id' => $employee_id])->update(['employee_name' => $employee_name, 'employee_surname' => $employee_surname, 'employee_position' => $employee_position]);
    }

    public static function updateEmployeeScoreOverall($employee_id, $employee_overall){
        EmployeeDimension::where(['employee_id' => $employee_id])->update(['employee_overall' => $employee_overall]);
        ShiftFacts::where(['employee_id' => $employee_id])->update(['employee_overall' => $employee_overall]);
    }

    public static function updateCompanyDimension($company_id, $company_name, $company_city, $company_street, $company_user_name, $company_user_surname){
        CompanyDimension::where(['company_id' => $company_id])->update(['company_name' => $company_name, 'company_city' => $company_city, 'company_street' => $company_street, 'company_user_name' => $company_user_name, 'company_user_surname' => $company_user_surname]);
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
            ,'shift_total_hours' => $total_hours, 'late_total_hours' => NULL, 'employee_injury_flag' => NULL, 'employee_overall' => $employee->employee_overall, 'employee_late_flag' => NULL, 'absence_reason' => NULL]);
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
