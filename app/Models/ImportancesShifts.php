<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImportancesShifts extends Model {
    /* Nazev souboru: ImportancesShifts.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_importances_shifts */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $primaryKey = 'importance_id';
    protected $table = 'table_importances_shifts';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = ['importance_description'];

    /* Nazev funkce: getParticularImportance
       Argumenty: importance_id - identifikator dulezitosti
       Ucel: ziskani aktualni dulezitosti smeny */
    public static function getParticularImportance($importance_id){
        date_default_timezone_set('Europe/Prague');
        return DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->join('table_shifts','table_shifts.shift_importance_id','=','table_importances_shifts.importance_id')
            ->where(['table_shifts.shift_importance_id' => $importance_id])
            ->distinct()
            ->get();
    }

    /* Nazev funkce: getAllImportances
       Argumenty: zadny
       Ucel: ziskani vsech moznych dulezitosti smeny */
    public static function getAllImportances(){
       return DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->get();
    }

    /* Nazev funkce: getAllImportancesExceptUnspecified
       Argumenty: zadny
       Ucel: ziskani vsech moznych dulezitosti smeny az na dulezitost Nespecifikovano */
    public static function getAllImportancesExceptUnspecified(){
       return DB::table('table_importances_shifts')
            ->select('table_importances_shifts.importance_id', 'table_importances_shifts.importance_description')
            ->whereIn('table_importances_shifts.importance_id',[1,2,3,4,5])
            ->get();
    }

}
