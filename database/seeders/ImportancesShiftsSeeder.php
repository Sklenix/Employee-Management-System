<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportancesShiftsSeeder extends Seeder {
    /* Nazev souboru: ImportancesShiftsSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_importances_shifts definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Fatální',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Důležité',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Normální',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Zaučení',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Nedůležité',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Nespecifikováno',
        ]);
    }
}
