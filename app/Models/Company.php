<?php

namespace App\Models;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * App\Models\Company
 *
 * @property int $company_id
 * @property string $company_name
 * @property string $company_user_name
 * @property string $company_user_surname
 * @property string $email
 * @property string|null $company_phone
 * @property string $company_login
 * @property string $company_url
 * @property string|null $company_picture
 * @property string $password
 * @property string|null $company_ico
 * @property string|null $company_city
 * @property string|null $company_street
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyIco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyPicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCompanyUserSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Company extends Authenticatable implements  MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'company_id';
    protected $table = 'table_companies';
    protected $guard = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name', 'company_user_name', 'company_user_surname','email','company_phone','company_login','password','company_url','company_picture','company_city','company_street','company_ico','company_dic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'company_password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    public static function getAverageEmployeeScore($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $skore = array();
        foreach ($zamestnanci as $zamestnanec){
            if($zamestnanec->employee_overall != NULL){
                array_push($skore,$zamestnanec->employee_overall);
            }
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($skore);$i++){
            $sum += $skore[$i];
        }
        if(sizeof($skore) == 0){
            return 0;
        }
        return round($sum/sizeof($skore),2);
    }

    public static function getAverageEmployeeReliabilityScore($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $skore = array();
        foreach ($zamestnanci as $zamestnanec){
            if($zamestnanec->employee_reliability != NULL){
                array_push($skore,$zamestnanec->employee_reliability);
            }
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($skore);$i++){
            $sum += $skore[$i];
        }
        if(sizeof($skore) == 0){
            return 0;
        }
        return round($sum/sizeof($skore),2);
    }

    public static function getAverageEmployeeAbsenceScore($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $skore = array();
        foreach ($zamestnanci as $zamestnanec){
            if($zamestnanec->employee_absence != NULL){
                array_push($skore,$zamestnanec->employee_absence);
            }
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($skore);$i++){
            $sum += $skore[$i];
        }
        if(sizeof($skore) == 0){
            return 0;
        }
        return round($sum/sizeof($skore),2);
    }

    public static function getAverageEmployeeWorkScore($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $skore = array();
        foreach ($zamestnanci as $zamestnanec){
            if($zamestnanec->employee_workindex != NULL){
                array_push($skore,$zamestnanec->employee_workindex);
            }
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($skore);$i++){
            $sum += $skore[$i];
        }
        if(sizeof($skore) == 0){
            return 0;
        }
        return round($sum/sizeof($skore),2);
    }

    public static function getAverageShiftHour($company_id){
        $smeny = Shift::getCompanyShifts($company_id);
        $delka = array();
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $shift_end = new DateTime($smena->shift_end);
            $hodinyRozdil = $shift_end->diff($shift_start);
            $celkove = $hodinyRozdil->h + ($hodinyRozdil->i/60);
            array_push($delka,$celkove);
        }
        $sum = 0;
        for ($i = 0; $i < sizeof($delka);$i++){
            $sum += $delka[$i];
        }
        if(sizeof($delka) == 0){
            return 0;
        }
        return round($sum/sizeof($delka),2);
    }

    public static function getMaxShiftHour($company_id){
        $smeny = Shift::getCompanyShifts($company_id);
        $delka = array();
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $shift_end = new DateTime($smena->shift_end);
            $hodinyRozdil = $shift_end->diff($shift_start);
            $celkove = $hodinyRozdil->h + ($hodinyRozdil->i/60);
            array_push($delka,$celkove);
        }
        if(sizeof($delka) == 0){
            return 0;
        }
        return round(max($delka),2);
    }

    public static function getMinShiftHour($company_id){
        $smeny = Shift::getCompanyShifts($company_id);
        $delka = array();
        foreach ($smeny as $smena){
            $shift_start = new DateTime($smena->shift_start);
            $shift_end = new DateTime($smena->shift_end);
            $hodinyRozdil = $shift_end->diff($shift_start);
            $celkove = $hodinyRozdil->h + ($hodinyRozdil->i/60);
            array_push($delka,$celkove);
        }
        if(sizeof($delka) == 0){
            return 0;
        }
        return round(min($delka),2);
    }

    public static function getNewEmployeesCountByMonths($company_id){
        $zamestnanci = DB::table('table_employees')
            ->select(DB::raw("COUNT(*) as count"))
            ->where('employee_company', $company_id)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $mesice = DB::table('table_employees')
            ->select(DB::raw("Month(created_at) as month"))
            ->where('employee_company', $company_id)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');

        $data_employees = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice as $index => $month){
            $data_employees[$month - 1] = $zamestnanci[$index];
        }
        return $data_employees;
    }

    public static function getNewShiftsCountByMonths($company_id){
        $smeny = DB::table('table_shifts')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->where('company_id', $company_id)
            ->whereYear('shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('table_shifts')
            ->select(DB::raw("Month(shift_start) as month_shift"))
            ->where('company_id',$company_id)
            ->whereYear('shift_start', Carbon::now()->year)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function changeShiftsYear($company_id, $rok){
        $smeny = DB::table('table_shifts')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->where('company_id', $company_id)
            ->whereYear('shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('table_shifts')
            ->select(DB::raw("Month(shift_start) as month_shift"))
            ->where('company_id', $company_id)
            ->whereYear('shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_start)"))
            ->pluck('month_shift');
        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function changeEmployeesYear($company_id, $rok){
        $zamestnanci = DB::table('table_employees')
            ->select(DB::raw("COUNT(*) as count"))
            ->where('employee_company', $company_id)
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $mesice = DB::table('table_employees')
            ->select(DB::raw("Month(created_at) as month"))
            ->where('employee_company', $company_id)
            ->whereYear('created_at', $rok)
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');

        $data_employees = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice as $index => $month){
            $data_employees[$month - 1] = $zamestnanci[$index];
        }
        return $data_employees;
    }

    public static function changeShiftsAssignedYear($company_id, $rok)
    {
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(*) as count_shift"))
            ->join('shift_facts', 'shift_info_dimension.shift_info_id', '=', 'shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_shift');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts', 'shift_info_dimension.shift_info_id', '=', 'shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        foreach ($mesice_smeny as $index => $month_shift) {
            $data_shifts[$month_shift - 1] = $smeny[$index];
        }
        return $data_shifts;
    }

    public static function changeShiftsTotalHoursYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(shift_total_hours,0)) as sum_shift_total_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_total_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_hodiny[$index];
        }
        return $data_shifts;
    }

    public static function changeShiftsTotalWorkedHoursYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_odpracovane_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(total_worked_hours,0)) as sum_shift_total_worked_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_total_worked_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('month_shift');

        $data_shifts = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_smeny as $index => $month_shift){
            $data_shifts[$month_shift - 1] = $smeny_odpracovane_hodiny[$index];
        }
        return $data_shifts;
    }

    public static function changeShiftsTotalLateHoursYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_zpozdeni_hodiny = DB::table('shift_info_dimension')
            ->select(DB::raw("SUM(IFNULL(late_total_hours,0)) as sum_shift_late_total_hours"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('sum_shift_late_total_hours');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
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

    public static function changeShiftsTotalLateFlagsCountYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_late_flagy = DB::table('shift_info_dimension')
            ->select(DB::raw("COUNT(employee_late_flag) as count_employee_late_flags"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupBy(DB::raw("Month(shift_info_dimension.shift_start)"))
            ->pluck('count_employee_late_flags');

        $mesice_smeny = DB::table('shift_info_dimension')
            ->select(DB::raw("Month(shift_info_dimension.shift_start) as month_shift"))
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
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

    public static function changeShiftsTotalInjuriesFlagsCountYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zraneni = DB::table('table_injuries')
            ->select(DB::raw("COUNT(*) as count_injuries"))
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->whereYear('table_injuries.injury_date', $rok)
            ->groupBy(DB::raw("Month(table_injuries.injury_date)"))
            ->pluck('count_injuries');

        $mesice_zraneni = DB::table('table_injuries')
            ->select(DB::raw("Month(table_injuries.injury_date) as month_injury"))
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->whereYear('table_injuries.injury_date', $rok)
            ->groupBy(DB::raw("Month(table_injuries.injury_date)"))
            ->pluck('month_injury');

        $data_injuries = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_zraneni as $index => $month_shift){
            $data_injuries[$month_shift - 1] = $zraneni[$index];
        }
        return $data_injuries;
    }

    public static function changeVacationsYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $dovolene = DB::table('table_vacations')
            ->select(DB::raw("COUNT(*) as count_vacations"))
            ->join('table_employees','table_vacations.employee_id','=','table_employees.employee_id')
            ->whereIn('table_vacations.employee_id',$id_zamestnancu)
            ->whereYear('table_vacations.vacation_start', $rok)
            ->groupBy(DB::raw("Month(table_vacations.vacation_start)"))
            ->pluck('count_vacations');

        $mesice_dovolene = DB::table('table_vacations')
            ->select(DB::raw("Month(table_vacations.vacation_start) as month_vacation"))
            ->whereIn('table_vacations.employee_id',$id_zamestnancu)
            ->whereYear('table_vacations.vacation_start', $rok)
            ->groupBy(DB::raw("Month(table_vacations.vacation_start)"))
            ->pluck('month_vacation');
        $data_vacations = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_dovolene as $index => $month_shift){
            $data_vacations[$month_shift - 1] = $dovolene[$index];
        }
        return $data_vacations;
    }

    public static function changeDiseasesYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $nemocenske = DB::table('table_diseases')
            ->select(DB::raw("COUNT(*) as count_disease"))
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->whereYear('table_diseases.disease_from', $rok)
            ->groupBy(DB::raw("Month(table_diseases.disease_from)"))
            ->pluck('count_disease');

        $mesice_nemocenske = DB::table('table_diseases')
            ->select(DB::raw("Month(table_diseases.disease_from) as month_disease"))
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->whereYear('table_diseases.disease_from', $rok)
            ->groupBy(DB::raw("Month(table_diseases.disease_from)"))
            ->pluck('month_disease');
        $data_diseases = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach ($mesice_nemocenske as $index => $month_shift){
            $data_diseases[$month_shift - 1] = $nemocenske[$index];
        }
        return $data_diseases;
    }

    public static function changeReportsYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $nahlaseni = DB::table('table_reports')
            ->select(DB::raw("COUNT(*) as count_reports"))
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereYear('table_reports.created_at', $rok)
            ->groupBy(DB::raw("Month(table_reports.created_at)"))
            ->pluck('count_reports');

        $mesice_nahlaseni = DB::table('table_reports')
            ->select(DB::raw("Month(table_reports.created_at) as month_report"))
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
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
