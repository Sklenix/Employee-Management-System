<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Languages extends Model {
    /* Nazev souboru: Languages.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce table_company_languages */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    public $timestamps = false;
    protected $primaryKey = 'language_id';
    protected $table = 'table_company_languages';
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'language_name','company_id'
    ];

    /* Nazev funkce: getCompanyLanguages
       Argumenty: company_id - identifikator firmy
       Ucel: ziskani vsech jazyku, ktere dana firma vytvorila */
    public static function getCompanyLanguages($company_id){
       return DB::table('table_company_languages')
                ->select('table_company_languages.language_name', 'table_company_languages.language_id')
                ->where(['table_company_languages.company_id' => $company_id])
                ->get();
    }

}
