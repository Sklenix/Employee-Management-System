<?php

namespace App\Models;

use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Employee
 *
 * @property int $employee_id
 * @property string $employee_name
 * @property string $employee_surname
 * @property string $employee_phone
 * @property string $email
 * @property string|null $employee_note
 * @property string $employee_position
 * @property string $employee_city
 * @property string|null $employee_street
 * @property string|null $employee_reliability
 * @property string|null $employee_absence
 * @property string|null $employee_workindex
 * @property string|null $employee_overall
 * @property string|null $employee_drive_url
 * @property string|null $employee_picture
 * @property string $employee_login
 * @property string|null $employee_department
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $employee_company
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeAbsence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeDriveUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeOverall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeReliability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmployeeWorkindex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'employee_id';
    protected $table = 'table_employees';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'employee_name', 'employee_surname','employee_phone', 'email','employee_note','employee_position','employee_city','employee_street',
        'employee_reliability','employee_absence','employee_workindex','employee_drive_url','employee_login','password','employee_company','employee_picture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function deleteData($id)
    {
        return static::find($id)->delete();
    }

    public function findData($id)
    {
        return static::find($id);
    }

    public function updateData($id, $input)
    {
        return static::find($id)->update($input);
    }

    public function storeData($input)
    {
        return static::create($input);
    }

    public static function getCompanyEmployees($company_id){
        return DB::table('table_employees')
          ->select('table_employees.employee_name','table_employees.employee_surname',
              'table_employees.employee_id','table_employees.employee_position','table_employees.employee_phone','table_employees.email','table_employees.employee_reliability',
              'table_employees.employee_absence','table_employees.employee_workindex','table_employees.employee_street','table_employees.employee_city','table_employees.employee_reliability',
              'table_employees.employee_absence','table_employees.employee_workindex','table_employees.employee_overall')
          ->where(['table_employees.employee_company' => $company_id])
          ->orderBy('table_employees.employee_name', 'asc')
          ->orderBy('table_employees.employee_surname', 'asc')
          ->orderBy('table_employees.employee_position', 'asc')
          ->get();
    }

    public static function getCompanyEmployeesAssigned($company_id){
        return DB::table('table_employee_shifts')
            ->select('table_employees.employee_name','table_employees.employee_surname',
                'table_employees.employee_id','table_employees.employee_position','table_employees.employee_phone','table_employees.email','table_employees.employee_reliability',
                'table_employees.employee_absence','table_employees.employee_workindex','table_employees.employee_street','table_employees.employee_city')
            ->join('table_employees','table_employee_shifts.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->orderBy('table_employees.employee_name', 'asc')
            ->distinct()
            ->get();
    }

    public static function getCompanyEmployeesCount($company_id){
        return DB::table('table_employees')
            ->select('table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->count();
    }

    public static function getShiftsEmployee($shift_id){
        return DB::table('table_employee_shifts')
            ->select('table_employees.employee_id','table_employees.employee_name','table_employees.employee_surname','table_employees.employee_position',
                'table_employees.employee_phone','table_employees.email','table_employees.employee_note')
            ->join('table_employees','table_employee_shifts.employee_id','=','table_employees.employee_id')
            ->where(['table_employee_shifts.shift_id' => $shift_id])
            ->get();
    }

    public static function getEmployeeTotalShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
       $smeny = Shift::getEmployeeShiftsWithEmployeeInformation($employee_id);
       $smenyPocetHodin = 0;
       foreach ($smeny as $smena){
           $shift_start = new DateTime($smena->shift_start);
           $shift_end = new DateTime($smena->shift_end);
           $hodinyRozdil = $shift_end->diff($shift_start);
           $pocetHodin = $hodinyRozdil->h;
           $pocetMinut = $hodinyRozdil->i;
           $smenyPocetHodin = $smenyPocetHodin + $pocetHodin + $pocetMinut/60;
       }
        $hodiny_celkove_arr = explode(".", $smenyPocetHodin);
        if(sizeof($hodiny_celkove_arr) > 1){
            $hodiny_celkove_arr[1] = substr( $hodiny_celkove_arr[1],0,2);
            $hodiny_celkove_arr[1]= round(($hodiny_celkove_arr[1]/100)*60,0);
            return $hodiny_celkove_arr[0].'h'.$hodiny_celkove_arr[1].'m';
        }else{
            return $hodiny_celkove_arr[0].'h0m';
        }
    }

    public static function getEmployeeWeekShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeCurrentShifts($employee_id);
        $smenyPocetHodin = 0;
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $shift_end = new DateTime($smena->shift_end);
            $hodinyRozdil = $shift_end->diff($shift_start);
            $pocetHodin = $hodinyRozdil->h;
            $pocetMinut = $hodinyRozdil->i;
            $smenyPocetHodin = $smenyPocetHodin + $pocetHodin + $pocetMinut/60;
        }
        $hodiny_celkove_arr = explode(".", $smenyPocetHodin);
        if(sizeof($hodiny_celkove_arr) > 1){
            $hodiny_celkove_arr[1] = substr( $hodiny_celkove_arr[1],0,2);
            $hodiny_celkove_arr[1]= round(($hodiny_celkove_arr[1]/100)*60,0);
            return $hodiny_celkove_arr[0].'h'.$hodiny_celkove_arr[1].'m';
        }else{
            return $hodiny_celkove_arr[0].'h0m';
        }
    }

    public static function getEmployeeMonthShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeCurrentMonthShifts($employee_id);
        $smenyPocetHodin = 0;
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $shift_end = new DateTime($smena->shift_end);
            $hodinyRozdil = $shift_end->diff($shift_start);
            $pocetHodin = $hodinyRozdil->h;
            $pocetMinut = $hodinyRozdil->i;
            $smenyPocetHodin = $smenyPocetHodin + $pocetHodin + $pocetMinut/60;
        }
        $hodiny_celkove_arr = explode(".", $smenyPocetHodin);
        if(sizeof($hodiny_celkove_arr) > 1){
            $hodiny_celkove_arr[1] = substr( $hodiny_celkove_arr[1],0,2);
            $hodiny_celkove_arr[1]= round(($hodiny_celkove_arr[1]/100)*60,0);
            return $hodiny_celkove_arr[0].'h'.$hodiny_celkove_arr[1].'m';
        }else{
            return $hodiny_celkove_arr[0].'h0m';
        }
    }

    public static function getEmployeeWorkedTotalShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeShiftsWithEmployeeInformation($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){
            $dochazka = DB::table('table_attendances')
                ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
                ->select('table_attendances.attendance_came','table_attendances.absence_reason_id',
                    'table_attendances.attendance_check_in', 'table_attendances.attendance_check_out','table_attendances.attendance_check_in_company',
                    'table_attendances.attendance_check_out_company')
                ->where(['table_attendances.shift_id' => $smena->shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
            if($dochazka->isEmpty()){
            }else{
                if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                    if($dochazka[0]->attendance_check_in != NULL || $dochazka[0]->attendance_check_out != NULL){
                        $checkin = new DateTime($dochazka[0]->attendance_check_in);
                        $checkout = new DateTime($dochazka[0]->attendance_check_out);
                        $hodinyRozdilCheck =$checkout->diff($checkin);
                        $celkove_odpracovano = $celkove_odpracovano + $hodinyRozdilCheck->h + $hodinyRozdilCheck->i/60;
                    }
                }else if($dochazka[0]->attendance_check_in_company != NULL && $dochazka[0]->attendance_check_out_company != NULL){
                    $checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                    $checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                    $hodinyRozdilCheck =$checkout->diff($checkin);
                    $celkove_odpracovano = $celkove_odpracovano + $hodinyRozdilCheck->h + $hodinyRozdilCheck->i/60;
                }
            }
        }
        $cas_odpracovano_arr = explode(".", $celkove_odpracovano);
        if(sizeof($cas_odpracovano_arr) > 1){
            $cas_odpracovano_arr[1] = substr($cas_odpracovano_arr[1],0,2);
            $cas_odpracovano_arr[1]= round(($cas_odpracovano_arr[1]/100)*60,0);
            return $cas_odpracovano_arr[0].'h'.$cas_odpracovano_arr[1].'m';
        }else{
            return $cas_odpracovano_arr[0].'h0m';
        }
    }

    public static function getEmployeeWorkedWeekShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeCurrentShifts($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){
            $dochazka = DB::table('table_attendances')
                ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
                ->select('table_attendances.attendance_came','table_attendances.absence_reason_id',
                    'table_attendances.attendance_check_in', 'table_attendances.attendance_check_out','table_attendances.attendance_check_in_company',
                    'table_attendances.attendance_check_out_company')
                ->where(['table_attendances.shift_id' => $smena->shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
            if($dochazka->isEmpty()){
            }else{
                if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                    if($dochazka[0]->attendance_check_in != NULL || $dochazka[0]->attendance_check_out != NULL){
                        $checkin = new DateTime($dochazka[0]->attendance_check_in);
                        $checkout = new DateTime($dochazka[0]->attendance_check_out);
                        $hodinyRozdilCheck =$checkout->diff($checkin);
                        $celkove_odpracovano = $celkove_odpracovano + $hodinyRozdilCheck->h + $hodinyRozdilCheck->i/60;
                    }
                }else if($dochazka[0]->attendance_check_in_company != NULL && $dochazka[0]->attendance_check_out_company != NULL){
                    $checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                    $checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                    $hodinyRozdilCheck =$checkout->diff($checkin);
                    $celkove_odpracovano = $celkove_odpracovano + $hodinyRozdilCheck->h + $hodinyRozdilCheck->i/60;
                }
            }
        }
        $cas_odpracovano_arr = explode(".", $celkove_odpracovano);
        if(sizeof($cas_odpracovano_arr) > 1){
            $cas_odpracovano_arr[1] = substr($cas_odpracovano_arr[1],0,2);
            $cas_odpracovano_arr[1]= round(($cas_odpracovano_arr[1]/100)*60,0);
            return $cas_odpracovano_arr[0].'h'.$cas_odpracovano_arr[1].'m';
        }else{
            return $cas_odpracovano_arr[0].'h0m';
        }
    }

    public static function getEmployeeWorkedMonthShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeCurrentMonthShifts($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){
            $dochazka = DB::table('table_attendances')
                ->join('table_employees', 'table_attendances.employee_id', '=', 'table_employees.employee_id')
                ->join('table_shifts', 'table_attendances.shift_id', '=', 'table_shifts.shift_id')
                ->join('table_employee_shifts', 'table_shifts.shift_id', '=', 'table_employee_shifts.shift_id')
                ->select('table_attendances.attendance_came','table_attendances.absence_reason_id',
                    'table_attendances.attendance_check_in', 'table_attendances.attendance_check_out','table_attendances.attendance_check_in_company',
                    'table_attendances.attendance_check_out_company')
                ->where(['table_attendances.shift_id' => $smena->shift_id,'table_attendances.employee_id' => $employee_id])
                ->get();
            if($dochazka->isEmpty()){
            }else{
                if ($dochazka[0]->attendance_check_in_company == NULL || $dochazka[0]->attendance_check_out_company == NULL){
                    if($dochazka[0]->attendance_check_in != NULL || $dochazka[0]->attendance_check_out != NULL){
                        $checkin = new DateTime($dochazka[0]->attendance_check_in);
                        $checkout = new DateTime($dochazka[0]->attendance_check_out);
                        $hodinyRozdilCheck =$checkout->diff($checkin);
                        $celkove_odpracovano = $celkove_odpracovano + $hodinyRozdilCheck->h + $hodinyRozdilCheck->i/60;
                    }
                }else if($dochazka[0]->attendance_check_in_company != NULL && $dochazka[0]->attendance_check_out_company != NULL){
                    $checkin = new DateTime($dochazka[0]->attendance_check_in_company);
                    $checkout = new DateTime($dochazka[0]->attendance_check_out_company);
                    $hodinyRozdilCheck =$checkout->diff($checkin);
                    $celkove_odpracovano = $celkove_odpracovano + $hodinyRozdilCheck->h + $hodinyRozdilCheck->i/60;
                }
            }
        }
        $cas_odpracovano_arr = explode(".", $celkove_odpracovano);
        if(sizeof($cas_odpracovano_arr) > 1){
            $cas_odpracovano_arr[1] = substr($cas_odpracovano_arr[1],0,2);
            $cas_odpracovano_arr[1]= round(($cas_odpracovano_arr[1]/100)*60,0);
            return $cas_odpracovano_arr[0].'h'.$cas_odpracovano_arr[1].'m';
        }else{
            return $cas_odpracovano_arr[0].'h0m';
        }
    }




}
