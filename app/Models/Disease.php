<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Disease extends Model {
    /* Nazev souboru: Disease.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_diseases */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky */
    protected $primaryKey = 'disease_id';
    protected $table = 'table_diseases';

    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'disease_name', 'disease_from','disease_to', 'disease_state', 'disease_note', 'employee_id'
    ];

    /* Nazev funkce: getEmployeeDiseases
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani nemocenskych zamestnance */
    public static function getEmployeeDiseases($employee_id){
        return DB::table('table_diseases')
            ->select('table_diseases.disease_id','table_diseases.disease_name',
                'table_diseases.disease_from','table_diseases.disease_to','table_diseases.disease_state',
                'table_diseases.disease_note','table_diseases.created_at','table_diseases.updated_at')
            ->where(['table_diseases.employee_id' => $employee_id])
            ->orderBy('table_diseases.disease_from', 'asc')
            ->get();
    }

    /* Nazev funkce: getEmployeeDiseasesCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu nemocenskych zamestnance */
    public static function getEmployeeDiseasesCount($employee_id){
        return DB::table('table_diseases')
            ->select('table_diseases.disease_id','table_diseases.disease_name',
                'table_diseases.disease_from','table_diseases.disease_to','table_diseases.disease_state',
                'table_diseases.disease_note','table_diseases.created_at','table_diseases.updated_at')
            ->where(['table_diseases.employee_id' => $employee_id])
            ->orderBy('table_diseases.disease_from', 'asc')
            ->count();
    }

    /* Nazev funkce: getCompanyDiseasesCount
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu nemocenskych v ramci firmy */
    public static function getCompanyDiseasesCount($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        return DB::table('table_diseases')
            ->select('table_diseases.disease_id','table_diseases.disease_name',
                'table_diseases.disease_from','table_diseases.disease_to','table_diseases.disease_state',
                'table_diseases.disease_note','table_diseases.created_at','table_diseases.updated_at')
            ->join('table_employees','table_diseases.employee_id','=','table_employees.employee_id')
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->orderBy('table_diseases.disease_from', 'asc')
            ->count();
    }

    /* Nazev funkce: getCompanyDiseasesByMonths
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani poctu nemocenskych dle mesicu v ramci firmy */
    public static function getCompanyDiseasesByMonths($company_id){
        date_default_timezone_set('Europe/Prague');
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        $nemocenske = DB::table('table_diseases')
            ->selectRaw('COUNT(*) as count_disease')
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->whereYear('table_diseases.disease_from', Carbon::now()->year)
            ->groupByRaw('MONTH(table_diseases.disease_from)')
            ->get();
        $mesice_nemocenske = DB::table('table_diseases')
            ->selectRaw('MONTH(table_diseases.disease_from) as month_disease')
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->whereYear('table_diseases.disease_from', Carbon::now()->year)
            ->groupByRaw('MONTH(table_diseases.disease_from)')
            ->get();
        $statistikaNemocenske = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_nemocenske); $i++){
            $statistikaNemocenske[$mesice_nemocenske[$i]->month_disease - 1] = $nemocenske[$i]->count_disease;
        }
        return $statistikaNemocenske;
    }

    /* Nazev funkce: getEmployeeDiseasesByMonths
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu nemocenskych dle mesicu v pripade konkretniho zamestnance */
    public static function getEmployeeDiseasesByMonths($employee_id){
        date_default_timezone_set('Europe/Prague');
        $nemocenske = DB::table('table_diseases')
            ->selectRaw('COUNT(*) as count_disease')
            ->where(['table_diseases.employee_id' => $employee_id])
            ->whereYear('table_diseases.disease_from', Carbon::now()->year)
            ->groupByRaw('MONTH(table_diseases.disease_from)')
            ->get();
        $mesice_nemocenske = DB::table('table_diseases')
            ->selectRaw('MONTH(table_diseases.disease_from) as month_disease')
            ->where(['table_diseases.employee_id' => $employee_id])
            ->whereYear('table_diseases.disease_from', Carbon::now()->year)
            ->groupByRaw('MONTH(table_diseases.disease_from)')
            ->get();
        $statistikaNemocenske = array(0,0,0,0,0,0,0,0,0,0,0,0);
        for ($i = 0; $i < sizeof($mesice_nemocenske); $i++){
            $statistikaNemocenske[$mesice_nemocenske[$i]->month_disease - 1] = $nemocenske[$i]->count_disease;
        }
        return $statistikaNemocenske;
    }

    /* Nazev funkce: getCompanyEmployeesDiseases
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani nemocenskych konkretni firmy */
    public static function getCompanyEmployeesDiseases($company_id){
        $zamestnanci = Employee::getCompanyEmployees($company_id);
        $id_zamestnancu = array();
        foreach ($zamestnanci as $zamestnanec){
            array_push($id_zamestnancu,$zamestnanec->employee_id);
        }
        return DB::table('table_diseases')
            ->select('table_diseases.disease_id','table_diseases.disease_name',
                'table_diseases.disease_from','table_diseases.disease_to','table_diseases.disease_state',
                'table_diseases.disease_note','table_diseases.created_at','table_diseases.updated_at','table_employees.employee_name',
                'table_employees.employee_surname','table_employees.employee_picture')
            ->join('table_employees','table_diseases.employee_id','=','table_employees.employee_id')
            ->whereIn('table_diseases.employee_id',$id_zamestnancu)
            ->whereNotIn('table_diseases.disease_state',[0])
            ->orderBy('table_diseases.disease_from', 'asc')
            ->get();
    }

}
