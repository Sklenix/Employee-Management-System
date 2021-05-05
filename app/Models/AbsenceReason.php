<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AbsenceReason extends Model {
    /* Nazev souboru: AbsenceReason.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_absence_reasons */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $primaryKey = 'reason_id';
    protected $table = 'table_absence_reasons';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = ['reason_description'];

    /* Nazev funkce: getAllReasons
       Argumenty: zadne
       Ucel: ziskani vsech duvodu absence (pro status dochazky) */
    public static function getAllReasons(){
        return DB::table('table_absence_reasons')
            ->select('table_absence_reasons.reason_id', 'table_absence_reasons.reason_description')
            ->get();
    }

    /* Nazev funkce: getParticularReason
       Argumenty: reason_id - konkretni id duvodu
       Ucel: ziskani konkretniho duvodu absence (statusu dochazky) */
    public static function getParticularReason($reason_id){
       return DB::table('table_absence_reasons')
            ->select('table_absence_reasons.reason_description')
            ->where(['table_absence_reasons.reason_id' => $reason_id])
            ->get();
    }

    /* Nazev funkce: getEmployeeCurrentShiftAbsenceReason
       Argumenty: zamestnanec_id - jednoznacny identifikator zamestnance, shift_id - jednoznacny identifikator smeny
       Ucel: ziskani duvodu absence zamestnance na konkretni smene (statusu dochazky) */
    public static function getEmployeeCurrentShiftAbsenceReason($zamestnanec_id, $shift_id){
       return DB::table('table_attendances')
            ->join('table_absence_reasons', 'table_attendances.absence_reason_id', '=', 'table_absence_reasons.reason_id')
            ->select('table_absence_reasons.reason_description')
            ->where(['table_attendances.shift_id' => $shift_id,'table_attendances.employee_id' => $zamestnanec_id])
            ->get();
    }

}
