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
            'importance_value' => '1',
            'importance_description' => 'Fatální',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '2',
            'importance_description' => 'Důležité',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '3',
            'importance_description' => 'Normální',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '4',
            'importance_description' => 'Zaučení',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '5',
            'importance_description' => 'Nedůležité',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '6',
            'importance_description' => 'Nespecifikováno',
        ]);
    }
}
