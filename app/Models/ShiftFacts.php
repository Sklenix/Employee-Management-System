<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftFacts extends Model {
    /* Nazev souboru: ShiftFacts.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida je modelem k tabulce shift_facts (soucast OLAP sekce systemu), jedna se o tabulku faktu */

    use HasFactory;
    /* Urceni nazvu tabulky a zruseni defaultnich atributu (created_at a updated_at) */
    protected $table = 'shift_facts';
    public $timestamps = false;
    /* Definice atributu tabulky, s kterymi model pracuje */
    protected $fillable = [
        'shift_total_hours', 'total_worked_hours', 'employee_late_flag', 'employee_injury_flag', 'absence_reason', 'late_total_hours',
        'employee_overall', 'company_id', 'time_id', 'employee_id', 'shift_info_id'
    ];
}
