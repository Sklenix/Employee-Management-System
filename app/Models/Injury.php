<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Injury extends Model {
    /* Nazev souboru: Injury.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_injuries */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky */
    protected $primaryKey = 'injury_id';
    protected $table = 'table_injuries';

    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'injury_description', 'injury_date','employee_id', 'shift_id'
    ];

    /* Nazev funkce: getInjuries
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani vsech zraneni v ramci firmy */
    public static function getInjuries($company_id){
        return DB::table('table_injuries')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_place',
                'table_employees.employee_name','table_employees.employee_surname','table_employees.employee_id',
                'table_injuries.injury_description','table_injuries.injury_date','table_injuries.created_at','table_injuries.updated_at','table_injuries.injury_id',
                'table_employees.employee_picture')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    /* Nazev funkce: getCompanyInjuriesCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu vsech zraneni v ramci firmy */
    public static function getCompanyInjuriesCount($company_id){
        return DB::table('table_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_shifts.company_id' => $company_id])
            ->count();
    }

    /* Nazev funkce: getEmployeeInjuriesCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu vsech zraneni konkretniho zamestnance */
    public static function getEmployeeInjuriesCount($employee_id){
        return DB::table('table_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->count();
    }

    /* Nazev funkce: getCompanyInjuriesByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu zraneni zamestnancu konkretni firme dle mesicu */
    public static function getCompanyInjuriesByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $zraneni = DB::table('table_injuries')
            ->selectRaw('COUNT(*) as count_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->whereYear('table_injuries.injury_date', Carbon::now()->year)
            ->groupByRaw('MONTH(table_injuries.injury_date)')
            ->get();
        $mesice_zraneni = DB::table('table_injuries')
            ->selectRaw('MONTH(table_injuries.injury_date) as month_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_company' => $company_id])
            ->whereYear('table_injuries.injury_date', Carbon::now()->year)
            ->groupByRaw('MONTH(table_injuries.injury_date)')
            ->get();
        $statistikaZraneni = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zraneni); $i++){
            $statistikaZraneni[$mesice_zraneni[$i]->month_injuries - 1] = $zraneni[$i]->count_injuries;
        }
        return $statistikaZraneni;
    }

    /* Nazev funkce: getEmployeeInjuriesByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu zraneni konkretniho zamestnance dle mesicu */
    public static function getEmployeeInjuriesByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $zraneni = DB::table('table_injuries')
            ->selectRaw('COUNT(*) as count_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->whereYear('table_injuries.injury_date', Carbon::now()->year)
            ->groupByRaw('MONTH(table_injuries.injury_date)')
            ->get();
        $mesice_zraneni = DB::table('table_injuries')
            ->selectRaw('MONTH(table_injuries.injury_date) as month_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->whereYear('table_injuries.injury_date', Carbon::now()->year)
            ->groupByRaw('MONTH(table_injuries.injury_date)')
            ->get();
        $statistikaZraneni = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_zraneni); $i++){
            $statistikaZraneni[$mesice_zraneni[$i]->month_injuries - 1] = $zraneni[$i]->count_injuries;
        }
        return $statistikaZraneni;
    }

    /* Nazev funkce: getEmployeeInjuries
       Argumenty: company_id - identifikator firmy, employee_id - identifikator zamestnance, shift_id - identifikator smeny
       Ucel: ziskani zraneni konkretniho zamestnance na konkretni smene v ramci konkretni firmy */
    public static function getEmployeeInjuries($company_id,$employee_id,$shift_id){
        return DB::table('table_injuries')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_place',
                'table_employees.employee_name','table_employees.employee_surname','table_employees.employee_id',
                'table_injuries.injury_description','table_injuries.injury_date','table_injuries.created_at','table_injuries.updated_at','table_injuries.injury_id',
                'table_employees.employee_picture')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_shifts.company_id' => $company_id,'table_employees.employee_id' => $employee_id,'table_shifts.shift_id' => $shift_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    /* Nazev funkce: getEmployeeInjuriesInjuryCentre
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani zraneni konkretniho zamestnance */
    public static function getEmployeeInjuriesInjuryCentre($employee_id){
        return DB::table('table_injuries')
            ->select('table_shifts.shift_id','table_shifts.shift_start','table_shifts.shift_end','table_shifts.shift_place',
                'table_employees.employee_id', 'table_injuries.injury_description','table_injuries.injury_date','table_injuries.created_at',
                'table_injuries.updated_at','table_injuries.injury_id')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->orderBy('table_shifts.shift_start', 'asc')
            ->distinct()
            ->get();
    }

    /* Nazev funkce: getEmployeeInjuriesInjuryCentreCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu zraneni konkretniho zamestnance */
    public static function getEmployeeInjuriesInjuryCentreCount($employee_id){
        return DB::table('table_injuries')
            ->join('table_shifts','table_injuries.shift_id','=','table_shifts.shift_id')
            ->join('table_employees','table_injuries.employee_id','=','table_employees.employee_id')
            ->where(['table_employees.employee_id' => $employee_id])
            ->distinct()
            ->count();
    }

}
