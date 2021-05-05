<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportancesReportsSeeder extends Seeder {
    /* Nazev souboru: ImportancesReportsSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_reports_importances definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Zásadní',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Naléhavé',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Důležité',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Normální',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Nedůležité',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Nespecifikováno',
        ]);
    }
}
