<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftsSeeder extends Seeder {
    /* Nazev souboru: ShiftsSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_shifts definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_shifts')->insert([
            'shift_id' => '1',
            'shift_start' => '2021-03-09 10:00:00',
            'shift_end' => '2021-03-09 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '2',
            'shift_start' => '2021-03-12 10:00:00',
            'shift_end' => '2021-03-12 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '3',
            'shift_start' => '2021-03-13 10:00:00',
            'shift_end' => '2021-03-13 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '4',
            'shift_start' => '2021-02-12 08:00:00',
            'shift_end' => '2021-02-12 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '5',
            'shift_start' => '2021-02-06 08:00:00',
            'shift_end' => '2021-02-06 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '6',
            'shift_start' => '2021-01-05 08:00:00',
            'shift_end' => '2021-01-05 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '7',
            'shift_start' => '2021-01-08 08:00:00',
            'shift_end' => '2021-01-08 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '8',
            'shift_start' => '2021-01-09 08:00:00',
            'shift_end' => '2021-01-09 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '6',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '9',
            'shift_start' => '2021-03-20 08:00:00',
            'shift_end' => '2021-03-20 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '10',
            'shift_start' => '2021-03-18 08:00:00',
            'shift_end' => '2021-03-18 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '11',
            'shift_start' => '2021-03-15 09:00:00',
            'shift_end' => '2021-03-15 17:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '12',
            'shift_start' => '2021-03-14 11:00:00',
            'shift_end' => '2021-03-14 19:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '13',
            'shift_start' => '2021-03-22 11:00:00',
            'shift_end' => '2021-03-22 19:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '14',
            'shift_start' => '2021-03-23 11:00:00',
            'shift_end' => '2021-03-23 19:00:00',
            'shift_place' => 'Praha',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '15',
            'shift_start' => '2021-03-24 10:00:00',
            'shift_end' => '2021-03-24 18:00:00',
            'shift_place' => 'Praha',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '16',
            'shift_start' => '2021-03-25 09:00:00',
            'shift_end' => '2021-03-25 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '17',
            'shift_start' => '2021-03-26 13:00:00',
            'shift_end' => '2021-03-26 18:00:00',
            'shift_place' => 'Třebíč',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '18',
            'shift_start' => '2021-03-27 09:00:00',
            'shift_end' => '2021-03-27 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '19',
            'shift_start' => '2021-03-28 09:00:00',
            'shift_end' => '2021-03-28 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '20',
            'shift_start' => '2021-03-29 09:00:00',
            'shift_end' => '2021-03-29 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
