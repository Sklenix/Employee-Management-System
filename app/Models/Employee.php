<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Employee
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
class Employee extends Authenticate {
    /* Nazev souboru: Employee.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_employees */
    use HasFactory, Notifiable;
    /* Urceni primarniho klice tabulky, nazvu tabulky */
    protected $primaryKey = 'employee_id';
    protected $table = 'table_employees';
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
       'employee_name', 'employee_surname','employee_phone', 'email','employee_note','employee_position','employee_city','employee_street', 'employee_birthday',
        'employee_reliability','employee_absence','employee_workindex','employee_url','employee_login','password','employee_company','employee_picture'
    ];
    /* Atributy, ktere maji byt schovany pri vraceni udaju z databaze (pro bezpecnost) */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /* Nazev funkce: getCompanyEmployees
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani zamestnancu konkretni firmy */
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

    /* Nazev funkce: getCompanyEmployeesAssigned
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani prirazenych smen zamestnancu v ramci firmy */
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

    /* Nazev funkce: getCompanyEmployeesCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu zamestnancu firmy */
    public static function getCompanyEmployeesCount($company_id){
        return DB::table('table_employees')
            ->select('table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->count();
    }

    /* Nazev funkce: getShiftsEmployee
       Argumenty: shift_id - identifikator smeny
       Ucel: ziskani zamestnancu na konkretni smene */
    public static function getShiftsEmployee($shift_id){
        return DB::table('table_employee_shifts')
            ->select('table_employees.employee_id','table_employees.employee_name','table_employees.employee_surname','table_employees.employee_position',
                'table_employees.employee_phone','table_employees.email','table_employees.employee_note')
            ->join('table_employees','table_employee_shifts.employee_id','=','table_employees.employee_id')
            ->where(['table_employee_shifts.shift_id' => $shift_id])
            ->get();
    }

    /* Nazev funkce: getEmployeeTotalShiftsHour
    Argumenty: employee_id - identifikator zamestnance
    Ucel: ziskani delek zamestnancovych smen ve formatu xhxm napriklad 8h2m */
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

    /* Nazev funkce: getEmployeeWeekShiftsHour
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu hodin v ramci aktualniho tydne ve formatu xhxm napriklad 8h2m */
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

    /* Nazev funkce: getEmployeeWeekShiftsHourWithoutMinutesExtension
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu hodin v ramci aktualniho tydne bez formatu xhxm */
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

    /* Nazev funkce: getEmployeeMonthShiftsHour
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu hodin v ramci aktualniho mesice ve formatu xhxm (napriklad 8h2m) */
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

    /* Nazev funkce: getEmployeeMonthShiftsHourWithoutMinutesExtension
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu hodin v ramci aktualniho mesice bez formatu xhxm */
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

    /* Nazev funkce: getEmployeeWorkedTotalShiftsHour
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu celkove odpracovanych hodin konkretniho zamestnance ve formatu xhxm (napriklad 8h1m) */
    public static function getEmployeeWorkedTotalShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeShiftsWithEmployeeInformation($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){ // iterace skrze smeny
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena->shift_id, $employee_id);
            if($dochazka->isEmpty()){
            }else{ // sekce kodu pro vypocet odpracovanych hodin
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
        } // sekce kodu pro vytvoreni formatu xhxm
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

    /* Nazev funkce: getEmployeeWorkedTotalShiftsHourWithoutMinutesExtension
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu celkove odpracovanych hodin konkretniho zamestnance bez formatu xhxm (napriklad 8h1m) */
    public static function getEmployeeWorkedTotalShiftsHourWithoutMinutesExtension($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeShiftsWithEmployeeInformation($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena->shift_id, $employee_id);
            if($dochazka->isEmpty()){ // pokud dochazka neexistuje, nejsou vypocitany hodiny, pokud existuje pokracuje se vypoctem danych odpracovanych hodin
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

    /* Nazev funkce: getEmployeeWorkedWeekShiftsHour
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu odpracovanych hodin na smenach zamestnance za aktualni tyden ve formatu xhxm (napriklad 8h1m) */
    public static function getEmployeeWorkedWeekShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeCurrentShifts($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena->shift_id, $employee_id);
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

    /* Nazev funkce: getEmployeeWorkedWeekShiftsHourWithoutMinutesExtension
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu odpracovanych hodin na smenach zamestnance za aktualni tyden bez formatu xhxm (napriklad 8h1m) */
    public static function getEmployeeWorkedWeekShiftsHourWithoutMinutesExtension($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeCurrentShifts($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena->shift_id, $employee_id);
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

    /* Nazev funkce: getEmployeeWorkedMonthShiftsHour
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu odpracovanych hodin na smenach zamestnance za aktualni mesic ve formatu xhxm (napriklad 8h1m) */
    public static function getEmployeeWorkedMonthShiftsHour($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeCurrentMonthShifts($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena->shift_id, $employee_id);
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

    /* Nazev funkce: getEmployeeWorkedMonthShiftsHourWithoutMinutesExtension
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu odpracovanych hodin na smenach zamestnance za aktualni mesic bez formatu xhxm (napriklad 8h1m) */
    public static function getEmployeeWorkedMonthShiftsHourWithoutMinutesExtension($employee_id){
        date_default_timezone_set('Europe/Prague');
        $smeny = Shift::getEmployeeCurrentMonthShifts($employee_id);
        $celkove_odpracovano = 0;
        foreach ($smeny as $smena){
            $dochazka = Attendance::getEmployeeShiftParticularAttendance($smena->shift_id, $employee_id);
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

    /* Nazev funkce: changeShiftsAssignedYear
       Argumenty: employee_id - identifikator zamestnance, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu prirazenych smen dle mesicu */
    public static function changeShiftsAssignedYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(*) as count_shifts')
            ->join('shift_facts', 'shift_info_dimension.shift_info_id', '=', 'shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts')
            ->join('shift_facts', 'shift_info_dimension.shift_info_id', '=', 'shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts - 1] = $smeny[$i]->count_shifts;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalHoursYear
       Argumenty: employee_id - identifikator zamestnance, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu hodin smen zamestnance dle mesicu */
    public static function changeShiftsTotalHoursYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(shift_total_hours,0)) as sum_shift_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shift_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_hodiny); $i++){
            $statistikaSmen[$mesice_hodiny[$i]->month_shift_total_hours - 1] = round($smeny_hodiny[$i]->sum_shift_total_hours, 3);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalWorkedHoursYear
       Argumenty: employee_id - identifikator zamestnance, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu odpracovanych hodin na smenach zamestnance dle mesicu */
    public static function changeShiftsTotalWorkedHoursYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_odpracovane_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(total_worked_hours,0)) as sum_shift_total_worked_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_odpracovane = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_total_worked_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_odpracovane); $i++){
            $statistikaSmen[$mesice_odpracovane[$i]->month_total_worked_hours - 1] = round($smeny_odpracovane_hodiny[$i]->sum_shift_total_worked_hours, 4);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalLateHoursYear
       Argumenty: employee_id - identifikator zamestnance, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu hodin zpozdeni na smenach zamestnance dle mesicu */
    public static function changeShiftsTotalLateHoursYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_zpozdeni_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(late_total_hours,0)) as sum_shift_late_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_zpozdeni = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_late_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zpozdeni); $i++){
            $statistikaSmen[$mesice_zpozdeni[$i]->month_late_total_hours - 1] = round($smeny_zpozdeni_hodiny[$i]->sum_shift_late_total_hours, 3);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalLateFlagsCountYear
       Argumenty: employee_id - identifikator zamestnance, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu zpozdeni na smenach zamestnance dle mesicu */
    public static function changeShiftsTotalLateFlagsCountYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_late_flagy = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(employee_late_flag) as count_employee_late_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.employee_id' => $employee_id])
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_zpozdeni = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_employee_late_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.employee_id', $employee_id)
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zpozdeni); $i++){
            $statistikaSmen[$mesice_zpozdeni[$i]->month_employee_late_flags - 1] = $smeny_late_flagy[$i]->count_employee_late_flags;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalInjuriesFlagsCountYear
       Argumenty: employee_id - identifikator zamestnance, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu zraneni na smenach zamestnance dle mesicu */
    public static function changeShiftsTotalInjuriesFlagsCountYear($employee_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zraneni = DB::table('table_injuries')
            ->selectRaw('COUNT(*) as count_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->whereYear('table_injuries.injury_date', $rok)
            ->groupByRaw('MONTH(table_injuries.injury_date)')
            ->get();
        $mesice_zraneni = DB::table('table_injuries')
            ->selectRaw('MONTH(table_injuries.injury_date) as month_injury')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->whereYear('table_injuries.injury_date', $rok)
            ->groupByRaw('MONTH(table_injuries.injury_date)')
            ->get();
        $statistikaZraneni = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zraneni); $i++){
            $statistikaZraneni[$mesice_zraneni[$i]->month_injury - 1] = $zraneni[$i]->count_injuries;
        }
        return $statistikaZraneni;
    }

}
