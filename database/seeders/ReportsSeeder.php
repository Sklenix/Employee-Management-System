<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsSeeder extends Seeder {
    /* Nazev souboru: ReportsSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_reports definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_reports')->insert([
            'report_id' => 1,
            'report_title' => 'Tiskárna',
            'report_description' => 'Doplnit papír',
            'report_state' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 5,
            'report_importance_id' => 3
        ]);
        DB::table('table_reports')->insert([
            'report_id' => 2,
            'report_title' => 'ESET',
            'report_description' => 'Aktualizovat na nejnovější verzi',
            'report_state' => 4,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 1,
            'report_importance_id' => 2
        ]);
    }
}
