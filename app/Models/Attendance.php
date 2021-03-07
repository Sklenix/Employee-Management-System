<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

/**
 * App\Models\Attendance
 *
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
class Attendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id';
    protected $table = 'table_attendances';

    public $timestamps = false;

    protected $fillable = [
        'attendance_came', 'attendance_note','attendance_check_in', 'attendance_check_out','attendance_check_in_company','attendance_check_out_company',
        'absence_reason_id','employee_id', 'shift_id'
    ];

    public static function getEmployeeAbsenceCount($employee_id){
        if (DB::table('table_attendances')->where('table_attendances.employee_id', $employee_id)->exists()) {
            return DB::table('table_attendances')
                ->select('table_shifts.shift_id')
                ->join('table_employees','table_attendances.employee_id','=','table_employees.employee_id')
                ->where(['table_attendances.employee_id' => $employee_id])
                ->whereIn('table_attendances.absence_reason_id' , [1,2,3,4])
                ->count();
        }else{
            return 0;
        }
    }

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

    public static function getEmployeeCurrentShiftAbsenceStatus($shift_id, $employee_id){
       return DB::table('table_absence_reasons')
            ->select('table_absence_reasons.reason_description','table_attendances.absence_reason_id')
            ->join('table_attendances','table_absence_reasons.reason_id','=','table_attendances.absence_reason_id')
            ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
            ->get();
    }

    public static function getEmployeeCheckIn($shift_id,$employee_id){
        return DB::table('table_attendances')
            ->select('table_attendances.attendance_check_in')
            ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
            ->get();
    }

    public static function getEmployeeCheckOut($shift_id,$employee_id){
        return DB::table('table_attendances')
                ->select('table_attendances.attendance_check_out')
                ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
    }

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
