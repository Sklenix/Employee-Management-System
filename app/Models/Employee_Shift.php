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
    protected $guard = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'shift_id', 'company_user_surname','email','company_phone','company_login','password','company_url','company_picture','company_city','company_street','company_ico','company_dic'
    ];


    public static function getEmployeeCurrentShiftsWithAttendance($employee_id){
        date_default_timezone_set('Europe/Prague');
        $now = Carbon::now();
        $pondeli = $now->startOfWeek()->format('Y-m-d H:i:s');
        $nedele = $now->endOfWeek()->format('Y-m-d H:i:s');
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id','table_employees.employee_id')
            ->join('table_employees','table_employee_shifts.employee_id','=','table_employees.employee_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->leftJoin('table_attendances','table_employee_shifts.shift_id','=','table_attendances.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->whereBetween('table_shifts.shift_start', [
                $pondeli,
                $nedele,
            ])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    public static function getEmployeeAllShiftsWithAttendance($employee_id){
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id','table_employees.employee_id',
                'table_attendances.attendance_check_in', 'table_attendances.attendance_check_out','table_attendances.attendance_check_in_company',
                'table_attendances.attendance_check_out_company')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_employee_shifts.employee_id','=','table_employees.employee_id')
            ->leftJoin('table_attendances','table_employee_shifts.shift_id','=','table_attendances.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

}
