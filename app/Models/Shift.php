<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Shift
 *
 * @property int $shift_id
 * @property string|null $shift_start
 * @property string|null $shift_end
 * @property string|null $shift_note
 * @property string|null $shift_place
 * @property int|null $shift_importance_id
 * @property int $company_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereShiftEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereShiftImportanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereShiftNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereShiftPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereShiftStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Shift extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'shift_id';
    protected $table = 'table_shifts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shift_start', 'shift_end','shift_note', 'shift_place','shift_importance_id','company_id'
    ];

    public function findData($id)
    {
        return static::find($id);
    }

    public function deleteData($id)
    {
        return static::find($id)->delete();
    }

    public static function getConcreteShift($shift_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start',
                'table_shifts.shift_end','table_shifts.shift_place','table_shifts.shift_note',
                'table_shifts.shift_importance_id')
            ->where(['table_shifts.shift_id' => $shift_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->get();
    }

    public static function getCompanyShifts($company_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start',
                'table_shifts.shift_end','table_shifts.shift_place','table_shifts.shift_note',
                'table_shifts.shift_importance_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->get();
    }

    public static function getUpcomingCompanyShifts($company_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start',
                'table_shifts.shift_end','table_shifts.shift_place','table_shifts.shift_note',
                'table_shifts.shift_importance_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->orderBy('table_shifts.shift_start', 'asc')
            ->get();
    }

    public static function getCompanyTotalShiftCount($company_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->count();
    }

    public static function getUpcomingCompanyShiftsCount($company_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->count();
    }

    public static function getHistoricalCompanyShiftsCount($company_id){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->where('table_shifts.shift_start', '<',  Carbon::now())
            ->count();
    }

    public static function getEmployeeShifts($employee_id){
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->get();
    }

    public static function getCurrentShiftImportance($shift_importance_id){
        return DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->join('table_shifts','table_shifts.shift_importance_id','=','table_importances_shifts.importance_id')
            ->where(['table_shifts.shift_importance_id' => $shift_importance_id])
            ->distinct()
            ->get();
    }

    public static function getEmployeeShiftsIds($employee_id){
        $result = DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->get();
        $result = $result->toArray();
        return $result;
    }

    public static function getCompanyShiftsAssigned($company_id){
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->orderBy('table_shifts.shift_end', 'asc')
            ->distinct()
            ->get();
    }

    public static function getEmployeeShiftsCount($employee_id){
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->count();
    }

    public static function getEmployeeUpcomingShiftsCount($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->where('table_shifts.shift_start', '>=',  Carbon::now())
            ->count();
    }

    public static function getEmployeesCountAtShift($shift_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_employee_shifts')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.shift_id' => $shift_id])
            ->count();
    }

    public static function getEmployeeCurrentShifts($employee_id){
            date_default_timezone_set('Europe/Prague');
            $now = Carbon::now();
            $pondeli = $now->startOfWeek()->format('Y-m-d H:i:s');
            $nedele = $now->endOfWeek()->format('Y-m-d H:i:s');
            return DB::table('table_employee_shifts')
                ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                    'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
                ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
                ->where(['table_employee_shifts.employee_id' => $employee_id])
               /* ->whereBetween('table_shifts.shift_start', [
                    Carbon::parse('this monday')->startOfDay(),
                    Carbon::parse('this sunday')->endOfDay(),
                ])*/
               ->whereBetween('table_shifts.shift_start', [
                   $pondeli,
                   $nedele,
               ])
                ->orderBy('table_shifts.shift_start', 'asc')
                ->distinct()
                ->get();
    }

    public static function getEmployeeCurrentMonthShifts($employee_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_employee_shifts')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_note','table_shifts.shift_importance_id')
            ->join('table_shifts','table_employee_shifts.shift_id','=','table_shifts.shift_id')
            ->where(['table_employee_shifts.employee_id' => $employee_id])
            ->whereMonth('table_shifts.shift_start', Carbon::now()->month)
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    public static function getEmployeeShiftsWithEmployeeInformation($employee_id){
        return DB::table('table_employee_shifts')
            ->join('table_employees', 'table_employee_shifts.employee_id', '=', 'table_employees.employee_id')
            ->join('table_shifts', 'table_employee_shifts.shift_id', '=', 'table_shifts.shift_id')
            ->select('table_shifts.shift_start','table_shifts.shift_end',
                'table_shifts.shift_place','table_shifts.shift_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->orderByDesc('table_shifts.shift_start')
            ->get();
    }


    public static function getCurrentImportanceShift($importance_id){
        return DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->join('table_shifts','table_shifts.shift_importance_id','=','table_importances_shifts.importance_id')
            ->where(['table_shifts.shift_importance_id' => $importance_id])
            ->get();
    }
}
