<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Report_Importance
 * @property int $importance_report_id
 * @property string|null $importance_report_value
 * @property string|null $importance_report_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereImportanceReportDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereImportanceReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereImportanceReportValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
    protected $fillable = [
        'importance_report_value', 'importance_report_description'
    ];

    /* Nazev funkce: getAllReportsImportancesOptions
       Argumenty: zadne
       Ucel: ziskani vsech moznosti dulezitosti nahlaseni */
    public static function getAllReportsImportancesOptions(){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id', 'table_reports_importances.importance_report_value',
                'table_reports_importances.importance_report_description',)
            ->where('table_reports_importances.importance_report_description', '<>', 'Nespecifikováno')
            ->orderBy('table_reports_importances.importance_report_value', 'asc')
            ->get();
    }

    /* Nazev funkce: getAllReportsImportancesOptionsWithUnspecified
       Argumenty: zadne
       Ucel: ziskani vsech moznosti dulezitosti nahlaseni az na moznost Nespecifikovano */
    public static function getAllReportsImportancesOptionsWithUnspecified(){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id', 'table_reports_importances.importance_report_value',
                'table_reports_importances.importance_report_description',)
            ->orderBy('table_reports_importances.importance_report_value', 'asc')
            ->get();
    }

    /* Nazev funkce: getConcreteImportance
       Argumenty: importance_id - identifikator dulezitosti
       Ucel: ziskani konkretni dulezitosti */
    public static function getConcreteImportance($importance_id){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id', 'table_reports_importances.importance_report_value',
                'table_reports_importances.importance_report_description',)
            ->where(['table_reports_importances.importance_report_id' => $importance_id])
            ->first();
    }

}
