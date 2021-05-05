<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftInfoDimension extends Model {
    /* Nazev souboru: ShiftInfoDimension.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce shift_info_dimension (soucast OLAP sekce systemu) */

    use HasFactory;
    /* Urceni primarniho klice tabulky, nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $table = 'shift_info_dimension';
    protected $primaryKey = 'shift_info_id';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'shift_info_id', 'shift_start', 'shift_end','attendance_came', 'attendance_check_in','attendance_check_out',
        'attendance_check_in_company', 'attendance_check_out_company', 'absence_reason'
    ];
}
