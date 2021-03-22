<?php

namespace App\Models;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * App\Models\Employee_Shift
 *
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
class Employee_Shift extends Authenticatable implements  MustVerifyEmail
{
    use HasFactory;
    protected $primaryKey = 'employee_shift_id';
    protected $table = 'table_employee_shifts';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'shift_id', 'company_user_surname','email','company_phone','company_login','password','company_url','company_picture','company_city','company_street','company_ico','company_dic'
    ];


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
            ->whereBetween('table_shifts.shift_start', [
                $pondeli,
                $nedele,
            ])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

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

    public static function getEmployeeParticularShift($employee_id, $shift_id){
        return DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_employee_shifts.employee_id','table_employee_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id, 'table_shifts.shift_id' => $shift_id])
            ->get();
    }

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

    public static function getAttendanceOptionsShifts($shift_id, $company_id){
       return DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_employee_shifts.employee_id','table_employees.employee_name','table_employees.employee_surname','table_shifts.shift_start')
            ->where(['table_shifts.shift_id' => $shift_id,'table_shifts.company_id' => $company_id])
            ->orderBy('table_employees.employee_surname', 'asc')
            ->get();
    }

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

    public static function deleteShiftFromShiftDatatable($shift_id){
        return DB::table('table_employee_shifts')
            ->select('table_employee_shifts.employee_id','table_employee_shifts.shift_id')
            ->where(['table_employee_shifts.shift_id' => $shift_id])
            ->delete();
    }

}
