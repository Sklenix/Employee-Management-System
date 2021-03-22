<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Admin
 *
 * @property int $admin_id
 * @property string $admin_name
 * @property string $admin_surname
 * @property string $admin_email
 * @property string $admin_login
 * @property string $admin_password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'admin_id';
    protected $table = 'table_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_name', 'admin_surname', 'admin_email','admin_password','admin_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'admin_password', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->admin_password;
    }

    public static function getCountOfAssignedShifts(){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->count();
    }

    public static function getCountUpcomingShiftsAssigned(){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where('shift_info_dimension.shift_start', '>=',  Carbon::now())
            ->count();
    }

    public static function getCompaniesEmployeesCount(){
        return DB::table('table_employees')
            ->select('table_employees.employee_id')
            ->count();
    }

    public static function getCompaniesCount(){
        return DB::table('table_companies')
            ->select('table_companies.company_id')
            ->count();
    }

    public static function getCompanyTotalShiftCount(){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id')
            ->count();
    }

    public static function getNewCompaniesCountByMonths(){
        $firmy = DB::table('table_companies')
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $mesice = DB::table('table_companies')
            ->select(DB::raw("Month(created_at) as month"))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');

        $data_companies = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice as $index => $month){
            $data_companies[$month - 1] = $firmy[$index];
        }
        return $data_companies;
    }

    public static function getNewCompaniesShiftsCountByMonths(){
        $smeny = DB::table('table_shifts')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->whereYear('shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('table_shifts')
            ->select(DB::raw("Month(shift_start) as month_shift"))
            ->whereYear('shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function getNewCompaniesEmployeesCountByMonths(){
        $zamestnanci = DB::table('table_employees')
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $mesice = DB::table('table_employees')
            ->select(DB::raw("Month(created_at) as month"))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');

        $data_employees = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice as $index => $month){
            $data_employees[$month - 1] = $zamestnanci[$index];
        }
        return $data_employees;
    }

    public static function getCountOfShiftsAssignedByMonths(){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function changeCompaniesGraphYear($rok){
        $firmy = DB::table('table_companies')
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $mesice = DB::table('table_companies')
            ->select(DB::raw("Month(created_at) as month"))
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');

        $data_companies = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice as $index => $month){
            $data_companies[$month - 1] = $firmy[$index];
        }
        return $data_companies;
    }

    public static function changeShiftsGraphYear($rok){
        $smeny = DB::table('table_shifts')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->whereYear('shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('table_shifts')
            ->select(DB::raw("Month(shift_start) as month_shift"))
            ->whereYear('shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function changeEmployeesGraphYear($rok){
        $zamestnanci = DB::table('table_employees')
            ->select(DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $mesice = DB::table('table_employees')
            ->select(DB::raw("Month(created_at) as month"))
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');

        $data_employees = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice as $index => $month){
            $data_employees[$month - 1] = $zamestnanci[$index];
        }
        return $data_employees;
    }

    public static function changeShiftsAssignedYear($rok){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

}
