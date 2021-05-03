<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsenceReasons extends Seeder{
    /* Nazev souboru: AbsenceReasons.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_absence_reasons definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '1',
            'reason_description' => 'Nemocný',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '2',
            'reason_description' => 'Nepřišel',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '3',
            'reason_description' => 'Odmítl',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '4',
            'reason_description' => 'Zpoždění',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '5',
            'reason_description' => 'OK',
        ]);
    }
}
