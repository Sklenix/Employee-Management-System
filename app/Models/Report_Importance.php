<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report_Importance extends Model {
    /* Nazev souboru: Report_Importance.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_reports_importances */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $primaryKey = 'report_id';
    protected $table = 'table_reports_importances';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = ['importance_report_description'];

    /* Nazev funkce: getAllReportsImportancesOptions
       Argumenty: zadne
       Ucel: ziskani vsech moznosti dulezitosti nahlaseni */
    public static function getAllReportsImportancesOptions(){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id',
                'table_reports_importances.importance_report_description',)
            ->where('table_reports_importances.importance_report_description', '<>', 'Nespecifikováno')
            ->orderBy('table_reports_importances.importance_report_id', 'asc')
            ->get();
    }

    /* Nazev funkce: getAllReportsImportancesOptionsWithUnspecified
       Argumenty: zadne
       Ucel: ziskani vsech moznosti dulezitosti nahlaseni az na moznost Nespecifikovano */
    public static function getAllReportsImportancesOptionsWithUnspecified(){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id',
                'table_reports_importances.importance_report_description',)
            ->orderBy('table_reports_importances.importance_report_id', 'asc')
            ->get();
    }

    /* Nazev funkce: getConcreteImportance
       Argumenty: importance_id - identifikator dulezitosti
       Ucel: ziskani konkretni dulezitosti */
    public static function getConcreteImportance($importance_id){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id',
                'table_reports_importances.importance_report_description',)
            ->where(['table_reports_importances.importance_report_id' => $importance_id])
            ->first();
    }

}
