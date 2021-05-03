<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InjuriesSeeder extends Seeder {
    /* Nazev souboru: InjuriesSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_injuries definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_injuries')->insert([
            'injury_id' => '1',
            'injury_description' => 'Vyvrtnutý kotník',
            'injury_date' => '2021-03-09 11:20:00',
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 1,
            'shift_id' => 1
        ]);
        DB::table('table_injuries')->insert([
            'injury_id' => '2',
            'injury_description' => 'Řezná rána na ruce',
            'injury_date' => '2021-02-12 15:05:00',
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 2,
            'shift_id' => 4
        ]);
        DB::table('table_injuries')->insert([
            'injury_id' => '3',
            'injury_description' => 'Vykloubené rameno',
            'injury_date' => '2021-03-20 13:10:00',
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 3,
            'shift_id' => 9
        ]);
        DB::table('table_injuries')->insert([
            'injury_id' => '4',
            'injury_description' => 'Upadnutí',
            'injury_date' => '2021-03-14 17:32:00',
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 5,
            'shift_id' => 12
        ]);
    }
}
