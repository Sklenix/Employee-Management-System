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
          ->orderBy('table_employees.employee_surname', 'asc')
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
            $scale = 1;
            for($i = 0; $i < strlen($hodiny_celkove_arr[1]); $i++){
                $scale *= 10;
            }
            $hodiny_celkove_arr[1]= round(($hodiny_celkove_arr[1]/$scale)*60,0);
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
            $scale = 1;
            for($i = 0; $i < strlen($hodiny_celkove_arr[1]); $i++){
                $scale *= 10;
            }
            $hodiny_celkove_arr[1]= round(($hodiny_celkove_arr[1]/100)*60,0);
            return $hodiny_celkove_arr[0].'h'.$hodiny_celkove_arr[1].'m';
        }else{
            return $hodiny_celkove_arr[0].'h0m';
        }
    }

    public static function getEmployeeWeekShiftsHourWithoutMinutesExtension($employee_id){
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
        return round($smenyPocetHodin, 2);
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
            $scale = 1;
            for($i = 0; $i < strlen($hodiny_celkove_arr[1]); $i++){
                $scale *= 10;
            }
            $hodiny_celkove_arr[1]= round(($hodiny_celkove_arr[1]/$scale)*60,0);
            return $hodiny_celkove_arr[0].'h'.$hodiny_celkove_arr[1].'m';
        }else{
            return $hodiny_celkove_arr[0].'h0m';
        }
    }

    public static function getEmployeeMonthShiftsHourWithoutMinutesExtension($employee_id){
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
       return round($smenyPocetHodin, 2);
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
            $scale = 1;
            for($i = 0; $i < strlen($cas_odpracovano_arr[1]); $i++){
                $scale *= 10;
            }
            $cas_odpracovano_arr[1]= round(($cas_odpracovano_arr[1]/$scale)*60,0);
            return $cas_odpracovano_arr[0].'h'.$cas_odpracovano_arr[1].'m';
        }else{
            return $cas_odpracovano_arr[0].'h0m';
        }
    }

    public static function getEmployeeWorkedTotalShiftsHourWithoutMinutesExtension($employee_id){
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
       return round($celkove_odpracovano, 3);
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
            $scale = 1;
            for($i = 0; $i < strlen($cas_odpracovano_arr[1]); $i++){
                $scale *= 10;
            }
            $cas_odpracovano_arr[1]= round(($cas_odpracovano_arr[1]/$scale)*60,0);
            return $cas_odpracovano_arr[0].'h'.$cas_odpracovano_arr[1].'m';
        }else{
            return $cas_odpracovano_arr[0].'h0m';
        }
    }

    public static function getEmployeeWorkedWeekShiftsHourWithoutMinutesExtension($employee_id){
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
       return round($celkove_odpracovano, 3);
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
            $scale = 1;
            for($i = 0; $i < strlen($cas_odpracovano_arr[1]); $i++){
                $scale *= 10;
            }
            $cas_odpracovano_arr[1]= round(($cas_odpracovano_arr[1]/$scale)*60,0);
            return $cas_odpracovano_arr[0].'h'.$cas_odpracovano_arr[1].'m';
        }else{
            return $cas_odpracovano_arr[0].'h0m';
        }
    }

    public static function getEmployeeWorkedMonthShiftsHourWithoutMinutesExtension($employee_id){
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
       return round($celkove_odpracovano, 3);
    }

    public static function changeShiftsAssignedYear($employee_id, $rok)
    {
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->join('shift_facts', 'shift_info_dimension.shift_info_id', '=', 'shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts', 'shift_info_dimension.shift_info_id', '=', 'shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        foreach ($mesice_smeny as $index => $month_shift) {
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function changeShiftsTotalHoursYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(shift_total_hours,0)) as sum_shift_total_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_total_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = round($smeny_hodiny[$index], 2);
        }
        return $data_shifts;
    }

    public static function changeShiftsTotalWorkedHoursYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_odpracovane_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(total_worked_hours,0)) as sum_shift_total_worked_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_total_worked_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_odpracovane_hodiny[$index];
        }
        return $data_shifts;
    }

    public static function changeShiftsTotalLateHoursYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_zpozdeni_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(late_total_hours,0)) as sum_shift_late_total_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_late_total_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_zpozdeni_hodiny[$index];
        }
        for ($i = 0; $i < sizeof($data_shifts); $i++){
            $data_shifts[$i] = round($data_shifts[$i],3);
        }
        return $data_shifts;
    }

    public static function changeShiftsTotalLateFlagsCountYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_late_flagy = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(employee_late_flag) as count_employee_late_flags"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_employee_late_flags');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_late_flagy[$index];
        }
        return $data_shifts;
    }

    public static function changeShiftsTotalInjuriesFlagsCountYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zraneni = DB::table('table_injuries')
            ->select(DB::raw("COUNT(*) as count_injuries"))
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->whereYear('table_injuries.injury_date', $rok)
            ->groupBy(DB::raw("Month(table_injuries.injury_date)"))
            ->pluck('count_injuries');

        $mesice_zraneni = DB::table('table_injuries')
            ->select(DB::raw("Month(table_injuries.injury_date) as month_injury"))
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->whereYear('table_injuries.injury_date', $rok)
            ->groupBy(DB::raw("Month(table_injuries.injury_date)"))
            ->pluck('month_injury');

        $data_injuries = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_zraneni as $index => $month_shift){
            $data_injuries[$month_shift - 1] = $zraneni[$index];
        }
        return $data_injuries;
    }

    public static function changeVacationsYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $dovolene = DB::table('table_vacations')
            ->select(DB::raw("COUNT(*) as count_vacations"))
            ->join('table_employees','table_vacations.employee_id','=','table_employees.employee_id')
            ->where(['table_vacations.employee_id' => $employee_id])
            ->whereYear('table_vacations.vacation_start', $rok)
            ->groupBy(DB::raw("Month(table_vacations.vacation_start)"))
            ->pluck('count_vacations');

        $mesice_dovolene = DB::table('table_vacations')
            ->select(DB::raw("Month(table_vacations.vacation_start) as month_vacation"))
            ->where(['table_vacations.employee_id' => $employee_id])
            ->whereYear('table_vacations.vacation_start', $rok)
            ->groupBy(DB::raw("Month(table_vacations.vacation_start)"))
            ->pluck('month_vacation');
        $data_vacations = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_dovolene as $index => $month_shift){
            $data_vacations[$month_shift - 1] = $dovolene[$index];
        }
        return $data_vacations;
    }

    public static function changeDiseasesYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $nemocenske = DB::table('table_diseases')
            ->select(DB::raw("COUNT(*) as count_disease"))
            ->where(['table_diseases.employee_id',$employee_id])
            ->whereYear('table_diseases.disease_from', $rok)
            ->groupBy(DB::raw("Month(table_diseases.disease_from)"))
            ->pluck('count_disease');

        $mesice_nemocenske = DB::table('table_diseases')
            ->select(DB::raw("Month(table_diseases.disease_from) as month_disease"))
            ->where(['table_diseases.employee_id',$employee_id])
            ->whereYear('table_diseases.disease_from', $rok)
            ->groupBy(DB::raw("Month(table_diseases.disease_from)"))
            ->pluck('month_disease');
        $data_diseases = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_nemocenske as $index => $month_shift){
            $data_diseases[$month_shift - 1] = $nemocenske[$index];
        }
        return $data_diseases;
    }

    public static function changeReportsYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $nahlaseni = DB::table('table_reports')
            ->select(DB::raw("COUNT(*) as count_reports"))
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id',$employee_id])
            ->whereYear('table_reports.created_at', $rok)
            ->groupBy(DB::raw("Month(table_reports.created_at)"))
            ->pluck('count_reports');

        $mesice_nahlaseni = DB::table('table_reports')
            ->select(DB::raw("Month(table_reports.created_at) as month_report"))
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->where(['table_reports.employee_id',$employee_id])
            ->whereYear('table_reports.created_at', $rok)
            ->groupBy(DB::raw("Month(table_reports.created_at)"))
            ->pluck('month_report');

        $data_reports = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_nahlaseni as $index => $month_shift){
            $data_reports[$month_shift - 1] = $nahlaseni[$index];
        }
        return $data_reports;
    }

}
