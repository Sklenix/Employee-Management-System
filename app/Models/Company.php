<?php

namespace App\Models;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Company extends Authenticate implements MustVerifyEmail {
    /* Nazev souboru: Company.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_companies */

    use HasFactory, Notifiable;
    /* Urceni primarniho klice tabulky, nazvu tabulky */
    protected $primaryKey = 'company_id';
    protected $table = 'table_companies';

    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'company_name', 'company_user_name', 'company_user_surname','email','company_phone','company_login','password','company_url','company_picture','company_city','company_street','company_ico','company_dic'
    ];

    /* Atributy, ktere maji byt schovany pri vraceni udaju z databaze (pro bezpecnost) */
    protected $hidden = [
        'company_password', 'remember_token',
    ];

    /* Urceni defaultniho formatu atributu email_verified_at */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* Funkce pro odeslani overovaciho emailu (vestavena v Laravelu) */
    public function sendEmailVerificationNotification(){
        $this->notify(new VerifyEmailNotification());
    }

    /* Nazev funkce: getAverageEmployeeScore
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani prumerneho skore zamestnance firmy */
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

    /* Nazev funkce: getAverageEmployeeReliabilityScore
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani prumerne spolehlivosti zamestnance */
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

    /* Nazev funkce: getAverageEmployeeAbsenceScore
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani prumerne dochvilnosti zamestnance */
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

    /* Nazev funkce: getAverageEmployeeWorkScore
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani prumerne pracovitosti zamestnance */
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

    /* Nazev funkce: getAverageShiftHour
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani prumerne delky smeny firmy */
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

    /* Nazev funkce: getMaxShiftHour
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani delky nejdelsi smeny firmy */
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

    /* Nazev funkce: getMinShiftHour
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani delky nejkratsi smeny firmy */
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

    /* Nazev funkce: getNewEmployeesCountByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu novych zamestnancu firmy dle mesicu */
    public static function getNewEmployeesCountByMonths($company_id){
        $zamestnanci = DB::table('table_employees')
                    ->selectRaw('COUNT(*) as count_employees')
                    ->where('employee_company', $company_id)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->groupByRaw('MONTH(created_at)')
                    ->get();
        $mesice_zamestnanci = DB::table('table_employees')
                    ->selectRaw('MONTH(created_at) as month_employees')
                    ->where('employee_company', $company_id)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->groupByRaw('MONTH(created_at)')
                    ->get();
        $statistikaZamestnanci = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zamestnanci); $i++){
            $statistikaZamestnanci[$mesice_zamestnanci[$i]->month_employees - 1] = $zamestnanci[$i]->count_employees;
        }
        return $statistikaZamestnanci;
    }

    /* Nazev funkce: getNewShiftsCountByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu vypsanych smen firmy dle mesicu */
    public static function getNewShiftsCountByMonths($company_id){
        $smeny = DB::table('table_shifts')
            ->selectRaw('COUNT(*) as count_shifts')
            ->where('company_id', $company_id)
            ->whereYear('shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_start)')
            ->get();
        $mesice_smeny = DB::table('table_shifts')
            ->selectRaw('MONTH(shift_start) as month_shifts')
            ->where('company_id',$company_id)
            ->whereYear('shift_start', Carbon::now()->year)
            ->groupByRaw('MONTH(shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts - 1] = $smeny[$i]->count_shifts;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu vypsanych smen dle mesicu */
    public static function changeShiftsYear($company_id, $rok) {
        $smeny = DB::table('table_shifts')
            ->selectRaw('COUNT(*) as count_shifts')
            ->where('company_id', $company_id)
            ->whereYear('shift_start', $rok)
            ->groupByRaw('MONTH(shift_start)')
            ->get();
        $mesice_smeny = DB::table('table_shifts')
            ->selectRaw('MONTH(shift_start) as month_shifts')
            ->where('company_id', $company_id)
            ->whereYear('shift_start', $rok)
            ->groupByRaw('MONTH(shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts - 1] = $smeny[$i]->count_shifts;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeEmployeesYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu novych zamestnancu firmy dle mesicu */
    public static function changeEmployeesYear($company_id, $rok){
        $zamestnanci = DB::table('table_employees')
            ->selectRaw('COUNT(*) as count_employees')
            ->where('employee_company', $company_id)
            ->whereYear('created_at', $rok)
            ->groupByRaw('MONTH(created_at)')
            ->get();
        $mesice_zamestnanci = DB::table('table_employees')
            ->selectRaw('MONTH(created_at) as month_employees')
            ->where('employee_company', $company_id)
            ->whereYear('created_at', $rok)
            ->groupByRaw('MONTH(created_at)')
            ->get();
        $statistikaZamestnancu = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zamestnanci); $i++){
            $statistikaZamestnancu[$mesice_zamestnanci[$i]->month_employees - 1] = $zamestnanci[$i]->count_employees;
        }
        return $statistikaZamestnancu;
    }

    /* Nazev funkce: changeShiftsAssignedYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu prirazenych smen v ramci firmy dle mesicu */
    public static function changeShiftsAssignedYear($company_id, $rok) {
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(*) as count_shifts')
            ->join('shift_facts', 'shift_info_dimension.shift_info_id', '=', 'shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts')
            ->join('shift_facts', 'shift_info_dimension.shift_info_id', '=', 'shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
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
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu celkovych delek smen dle mesicu */
    public static function changeShiftsTotalHoursYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(shift_total_hours,0)) as sum_shift_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $smeny_hodiny_mesice = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shift_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($smeny_hodiny_mesice); $i++){
            $statistikaSmen[$smeny_hodiny_mesice[$i]->month_shift_total_hours - 1] = $smeny_hodiny[$i]->sum_shift_total_hours;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalHoursYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu celkove odpracovanych hodin na smenach dle mesicu */
    public static function changeShiftsTotalWorkedHoursYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_odpracovane_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(total_worked_hours,0)) as sum_shift_total_worked_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts_total_worked_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts_total_worked_hours - 1] = $smeny_odpracovane_hodiny[$i]->sum_shift_total_worked_hours;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalLateHoursYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu celkovych hodin zpozdeni na smenach dle mesicu */
    public static function changeShiftsTotalLateHoursYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_zpozdeni_hodiny = DB::table('shift_info_dimension')
            ->selectRaw('SUM(IFNULL(late_total_hours,0)) as sum_shift_late_total_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny_zpozdeni = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts_late_hours')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny_zpozdeni); $i++){
            $statistikaSmen[$mesice_smeny_zpozdeni[$i]->month_shifts_late_hours - 1] = round($smeny_zpozdeni_hodiny[$i]->sum_shift_late_total_hours, 3);
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalLateFlagsCountYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu zpozdeni na smenach dle mesicu */
    public static function changeShiftsTotalLateFlagsCountYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $smeny_late_flagy = DB::table('shift_info_dimension')
            ->selectRaw('COUNT(employee_late_flag) as count_employee_late_flags')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_smeny_zpozdeni = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts_late')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->where(['shift_facts.employee_late_flag' => 1])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny_zpozdeni); $i++){
            $statistikaSmen[$mesice_smeny_zpozdeni[$i]->month_shifts_late - 1] = $smeny_late_flagy[$i]->count_employee_late_flags;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeShiftsTotalInjuriesFlagsCountYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu zraneni na smenach dle mesicu */
    public static function changeShiftsTotalInjuriesFlagsCountYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zraneni = DB::table('table_injuries')
            ->selectRaw('COUNT(*) as count_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->whereYear('table_injuries.injury_date', $rok)
            ->groupByRaw('MONTH(table_injuries.injury_date)')
            ->get();
        $mesice_zraneni = DB::table('table_injuries')
            ->selectRaw('MONTH(table_injuries.injury_date) as month_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->whereYear('table_injuries.injury_date', $rok)
            ->groupByRaw('MONTH(table_injuries.injury_date)')
            ->get();
        $statistikaZraneni = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zraneni); $i++){
            $statistikaZraneni[$mesice_zraneni[$i]->month_injuries - 1] = $zraneni[$i]->count_injuries;
        }
        return $statistikaZraneni;
    }

    /* Nazev funkce: changeVacationsYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu dovolenych dle mesicu */
    public static function changeVacationsYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $dovolene = DB::table('table_vacations')
            ->selectRaw('COUNT(*) as count_vacations')
            ->join('table_employees','table_vacations.employee_id','=','table_employees.employee_id')
            ->whereIn('table_vacations.employee_id',$id_zamestnancu)
            ->whereYear('table_vacations.vacation_start', $rok)
            ->groupByRaw('MONTH(table_vacations.vacation_start)')
            ->get();
        $mesice_dovolene = DB::table('table_vacations')
            ->selectRaw('MONTH(table_vacations.vacation_start) as month_vacation')
            ->whereIn('table_vacations.employee_id',$id_zamestnancu)
            ->whereYear('table_vacations.vacation_start', $rok)
            ->groupByRaw('MONTH(table_vacations.vacation_start)')
            ->get();
        $statistikaDovolene = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_dovolene); $i++){
            $statistikaDovolene[$mesice_dovolene[$i]->month_vacation - 1] = $dovolene[$i]->count_vacations;
        }
        return $statistikaDovolene;
    }

    /* Nazev funkce: changeDiseasesYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu nemocenskych dle mesicu */
    public static function changeDiseasesYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $nemocenske = DB::table('table_diseases')
            ->selectRaw('COUNT(*) as count_disease')
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->whereYear('table_diseases.disease_from', $rok)
            ->groupByRaw('MONTH(table_diseases.disease_from)')
            ->get();
        $mesice_nemocenske = DB::table('table_diseases')
            ->selectRaw('MONTH(table_diseases.disease_from) as month_disease')
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->whereYear('table_diseases.disease_from', $rok)
            ->groupByRaw('MONTH(table_diseases.disease_from)')
            ->get();
        $statistikaNemocenskych = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_nemocenske); $i++){
            $statistikaNemocenskych[$mesice_nemocenske[$i]->month_disease - 1] = $nemocenske[$i]->count_disease;
        }
        return $statistikaNemocenskych;
    }

    /* Nazev funkce: changeReportsYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu poctu nahlaseni dle mesicu */
    public static function changeReportsYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $nahlaseni = DB::table('table_reports')
            ->selectRaw('COUNT(*) as count_reports')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereYear('table_reports.created_at', $rok)
            ->groupByRaw('MONTH(table_reports.created_at)')
            ->get();
        $mesice_nahlaseni = DB::table('table_reports')
            ->selectRaw('MONTH(table_reports.created_at) as month_report')
            ->join('table_reports_importances','table_reports.report_importance_id','=','table_reports_importances.importance_report_id')
            ->whereIn('table_reports.employee_id',$id_zamestnancu)
            ->whereYear('table_reports.created_at', $rok)
            ->groupByRaw('MONTH(table_reports.created_at)')
            ->get();
        $statistikaNahlaseni = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_nahlaseni); $i++){
            $statistikaNahlaseni[$mesice_nahlaseni[$i]->month_report - 1] = $nahlaseni[$i]->count_reports;
        }
        return $statistikaNahlaseni;
    }

    /* Nazev funkce: changeAverageEmployeesScoresYear
       Argumenty: company_id - identifikator firmy, rok - zvoleny rok
       Ucel: zmena roku u grafu prumerneho skore zamestnance dle mesicu */
    public static function changeAverageEmployeesScoresYear($company_id, $rok){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci_skore = DB::table('shift_info_dimension')
            ->selectRaw('IFNULL(SUM(IFNULL(employee_overall,0)) / COUNT(employee_overall),0) as avg_employee_overall')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where(['shift_facts.company_id' => $company_id])
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $mesice_skore = DB::table('shift_info_dimension')
            ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_score')
            ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
            ->where('shift_facts.company_id', $company_id)
            ->whereYear('shift_info_dimension.shift_start', $rok)
            ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
            ->get();
        $statistikaSkore = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_skore); $i++){
            $statistikaSkore[$mesice_skore[$i]->month_score - 1] = $zamestnanci_skore[$i]->avg_employee_overall;
        }
        return $statistikaSkore;
    }

}
