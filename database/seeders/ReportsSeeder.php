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
            'report_description' => 'Doplnit papír.',
            'report_state' => 2,
            'created_at' => '2021-02-05 11:00:00',
            'updated_at' => now(),
            'employee_id' => 5,
            'report_importance_id' => 3
        ]);
        DB::table('table_reports')->insert([
            'report_id' => 2,
            'report_title' => 'ESET',
            'report_description' => 'Aktualizovat na nejnovější verzi.',
            'report_state' => 4,
            'created_at' => '2021-03-05 11:00:00',
            'updated_at' => now(),
            'employee_id' => 1,
            'report_importance_id' => 2
        ]);

        DB::table('table_reports')->insert([
            'report_id' => 3,
            'report_title' => 'Windows',
            'report_description' => 'Aktualizovat na nejnovější verzi.',
            'report_state' => 2,
            'created_at' => '2021-03-06 15:00:00',
            'updated_at' => now(),
            'employee_id' => 4,
            'report_importance_id' => 2
        ]);

        DB::table('table_reports')->insert([
            'report_id' => 4,
            'report_title' => 'Myčka',
            'report_description' => 'Přestala fungovat.',
            'report_state' => 2,
            'created_at' => '2021-03-09 13:00:00',
            'updated_at' => now(),
            'employee_id' => 3,
            'report_importance_id' => 1
        ]);

        DB::table('table_reports')->insert([
            'report_id' => 5,
            'report_title' => 'Frézka',
            'report_description' => 'Přestala fungovat.',
            'report_state' => 2,
            'created_at' => '2021-04-10 12:00:00',
            'updated_at' => now(),
            'employee_id' => 2,
            'report_importance_id' => 1
        ]);
    }
}
