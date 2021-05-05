<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Admin extends Authenticate {
    /* Nazev souboru: Admin.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_admins */

    use HasFactory, Notifiable;
    /* Urceni primarniho klice tabulky, nazvu tabulky */
    protected $primaryKey = 'admin_id';
    protected $table = 'table_admins';

    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'admin_name', 'admin_surname', 'admin_email','password','admin_login'
    ];

   /* Atributy, ktere maji byt schovany pri vraceni udaju z databaze (pro bezpecnost) */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /* Nazev funkce: getCountOfAssignedShifts
       Argumenty: zadne
       Ucel: ziskani poctu vsech prirazenych smen v systemu */
    public static function getCountOfAssignedShifts(){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->count();
    }

    /* Nazev funkce: getCountUpcomingShiftsAssigned
       Argumenty: zadne
       Ucel: ziskani poctu budoucich prirazenych smen */
    public static function getCountUpcomingShiftsAssigned(){
        date_default_timezone_set('Europe/Prague');
        return DB::table('shift_facts')
            ->join('shift_info_dimension','shift_facts.shift_info_id','=','shift_info_dimension.shift_info_id')
            ->where('shift_info_dimension.shift_start', '>=',  Carbon::now())
            ->count();
    }

    /* Nazev funkce: getCompaniesEmployeesCount
       Argumenty: zadne
       Ucel: ziskani poctu zamestnancu v systemu */
    public static function getCompaniesEmployeesCount(){
        return DB::table('table_employees')
            ->select('table_employees.employee_id')
            ->count();
    }

    /* Nazev funkce: getCompaniesCount
       Argumenty: zadne
       Ucel: ziskani poctu firem v systemu */
    public static function getCompaniesCount(){
        return DB::table('table_companies')
            ->select('table_companies.company_id')
            ->count();
    }

    /* Nazev funkce: getCompanyTotalShiftCount
       Argumenty: zadne
       Ucel: ziskani celkoveho poctu smen v systemu */
    public static function getCompanyTotalShiftCount(){
        return DB::table('table_shifts')
            ->select('table_shifts.shift_id')
            ->count();
    }

    /* Nazev funkce: getNewCompaniesCountByMonths
       Argumenty: zadne
       Ucel: ziskani poctu nove zaregistrovanych firem dle mesicu */
    public static function getNewCompaniesCountByMonths(){
        date_default_timezone_set('Europe/Prague');
        $firmy = DB::table('table_companies')
                    ->select(DB::raw('COUNT(*) as companies_count'))
                    ->whereYear('created_at', Carbon::now()->year)
                    ->groupByRaw('MONTH(created_at)')
                    ->get();
        $mesice_registrace = DB::table('table_companies')
                                ->selectRaw("MONTH(created_at) as companies_month")
                                ->whereYear('created_at', Carbon::now()->year)
                                ->groupByRaw('MONTH(created_at)')
                                ->get();
        $statistikaFirmy = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_registrace); $i++){
            $statistikaFirmy[$mesice_registrace[$i]->companies_month - 1] = $firmy[$i]->companies_count;
        }
        return $statistikaFirmy;
    }

    /* Nazev funkce: getNewCompaniesShiftsCountByMonths
       Argumenty: zadne
       Ucel: ziskani poctu vypsanych smen vsech firem dle mesicu */
    public static function getNewCompaniesShiftsCountByMonths(){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('table_shifts')
                    ->selectRaw('COUNT(*) as count_shift')
                    ->whereYear('shift_start', Carbon::now()->year)
                    ->groupByRaw('MONTH(shift_start)')
                    ->get();
        $mesice_smeny = DB::table('table_shifts')
                            ->selectRaw('MONTH(shift_start) as month_shift')
                            ->whereYear('shift_start', Carbon::now()->year)
                            ->groupByRaw('MONTH(shift_start)')
                            ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shift - 1] = $smeny[$i]->count_shift;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: getNewCompaniesEmployeesCountByMonths
     Argumenty: zadne
     Ucel: ziskani poctu novych zamestnancu firem dle mesicu */
    public static function getNewCompaniesEmployeesCountByMonths(){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = DB::table('table_employees')
                        ->selectRaw('COUNT(*) as count_employees')
                        ->whereYear('created_at', Carbon::now()->year)
                        ->groupByRaw('MONTH(created_at)')
                        ->get();
        $mesice_zamestnanci = DB::table('table_employees')
                                ->selectRaw('MONTH(created_at) as month_employees')
                                ->whereYear('created_at', Carbon::now()->year)
                                ->groupByRaw('MONTH(created_at)')
                                ->get();
        $statistikaZamestnancu = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zamestnanci); $i++){
            $statistikaZamestnancu[$mesice_zamestnanci[$i]->month_employees - 1] = $zamestnanci[$i]->count_employees;
        }
        return $statistikaZamestnancu;
    }

    /* Nazev funkce: getCountOfShiftsAssignedByMonths
       Argumenty: zadne
       Ucel: ziskani poctu prirazenych smen dle mesicu */
    public static function getCountOfShiftsAssignedByMonths(){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
                    ->selectRaw('COUNT(*) as count_shifts')
                    ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
                    ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
                    ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
                    ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
                        ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts')
                        ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
                        ->whereYear('shift_info_dimension.shift_start', Carbon::now()->year)
                        ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
                        ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts - 1] = $smeny[$i]->count_shifts;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeCompaniesGraphYear
       Argumenty: rok - rok zvoleny firmou
       Ucel: zmena roku u grafu nove zaregistrovanych firem dle mesicu */
    public static function changeCompaniesGraphYear($rok){
        date_default_timezone_set('Europe/Prague');
        $firmy = DB::table('table_companies')
                    ->selectRaw('COUNT(*) as count_companies')
                    ->whereYear('created_at', $rok)
                    ->groupByRaw('MONTH(created_at)')
                    ->get();
        $mesice_firmy = DB::table('table_companies')
                        ->selectRaw("MONTH(created_at) as month_companies")
                        ->whereYear('created_at', $rok)
                        ->groupByRaw("MONTH(created_at)")
                        ->get();
        $statistikaFirmy = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_firmy); $i++){
            $statistikaFirmy[$mesice_firmy[$i]->month_companies - 1] = $firmy[$i]->count_companies;
        }
        return $statistikaFirmy;
    }

    /* Nazev funkce: changeShiftsGraphYear
       Argumenty: rok - rok zvoleny firmou
       Ucel: zmena roku u grafu vypsanych smen firem dle mesicu */
    public static function changeShiftsGraphYear($rok){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('table_shifts')
                    ->selectRaw('COUNT(*) as count_shifts')
                    ->whereYear('shift_start', $rok)
                    ->groupByRaw('MONTH(shift_start)')
                    ->get();
        $mesice_smeny = DB::table('table_shifts')
                        ->selectRaw('MONTH(shift_start) as month_shifts')
                        ->whereYear('shift_start', $rok)
                        ->groupByRaw('MONTH(shift_start)')
                        ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts - 1] = $smeny[$i]->count_shifts;
        }
        return $statistikaSmen;
    }

    /* Nazev funkce: changeEmployeesGraphYear
       Argumenty: rok - rok zvoleny firmou
       Ucel: zmena roku u grafu vypsanych smen firem dle mesicu */
    public static function changeEmployeesGraphYear($rok){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = DB::table('table_employees')
                        ->selectRaw('COUNT(*) as count_employees')
                        ->whereYear('created_at', $rok)
                        ->groupByRaw('MONTH(created_at)')
                        ->get();
        $mesice_zamestnanci = DB::table('table_employees')
                        ->selectRaw('MONTH(created_at) as month_employees')
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
       Argumenty: rok - rok zvoleny firmou
       Ucel: zmena roku u grafu obsazenych smen firem dle mesicu */
    public static function changeShiftsAssignedYear($rok){
        date_default_timezone_set('Europe/Prague');
        $smeny = DB::table('shift_info_dimension')
                ->selectRaw('COUNT(*) as count_shifts')
                ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
                ->whereYear('shift_info_dimension.shift_start', $rok)
                ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
                ->get();
        $mesice_smeny = DB::table('shift_info_dimension')
                ->selectRaw('MONTH(shift_info_dimension.shift_start) as month_shifts')
                ->join('shift_facts','shift_info_dimension.shift_info_id','=','shift_facts.shift_info_id')
                ->whereYear('shift_info_dimension.shift_start', $rok)
                ->groupByRaw('MONTH(shift_info_dimension.shift_start)')
                ->get();
        $statistikaSmen = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_smeny); $i++){
            $statistikaSmen[$mesice_smeny[$i]->month_shifts - 1] = $smeny[$i]->count_shifts;
        }
        return $statistikaSmen;
    }

}
