<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee_Language extends Model {
    /* Nazev souboru: Employee_Language.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_employee_table_languages */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $primaryKey = 'language_employee_id';
    protected $table = 'table_employee_table_languages';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'language_id','employee_id'
    ];

    /* Nazev funkce: getEmployeeLanguages
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani jazyku zamestnance */
    public static function getEmployeeLanguages($employee_id){
        return DB::table('table_company_languages')
                    ->select('table_company_languages.language_name')
                    ->join('table_employee_table_languages','table_employee_table_languages.language_id','=','table_company_languages.language_id')
                    ->where(['table_employee_table_languages.employee_id' => $employee_id])
                    ->get();
    }

    /* Nazev funkce: getEmployeeLanguagesCount
       Argumenty: employee_id - identifikator zamestnance
       Ucel: ziskani poctu jazyku zamestnance */
    public static function getEmployeeLanguagesCount($employee_id){
         return DB::table('table_employee_table_languages')
                    ->select('table_employee_table_languages.language_employee_id')
                    ->where(['table_employee_table_languages.employee_id' => $employee_id])
                    ->count();
    }

    /* Nazev funkce: getEmployeeParticularLanguageCount
       Argumenty: employee_id - identifikator zamestnance, id - identifikator jazyka
       Ucel: zjisteni poctu vyskytu konkretniho jazyka zamestnance (pro validaci) */
    public static function getEmployeeParticularLanguageCount($employee_id, $id){
         return DB::table('table_employee_table_languages')
                    ->select('table_employee_table_languages.language_employee_id')
                    ->where(['table_employee_table_languages.employee_id' => $employee_id, 'table_employee_table_languages.language_id' => $id])
                    ->count();
    }

}
