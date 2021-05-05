<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VacationsSeeder extends Seeder {
    /* Nazev souboru: VacationsSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_vacations definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_vacations')->insert([
            'vacation_id' => 1,
            'vacation_start' => '2021-02-04 11:00:00',
            'vacation_end' => '2021-02-21 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 2,
            'created_at' => '2021-02-04 11:00:00',
            'updated_at' => now(),
            'employee_id' => 5,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 2,
            'vacation_start' => '2021-03-05 11:00:00',
            'vacation_end' => '2021-03-15 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 3,
            'created_at' => '2021-03-05 11:00:00',
            'updated_at' => now(),
            'employee_id' => 1,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 3,
            'vacation_start' => '2021-03-21 11:00:00',
            'vacation_end' => '2021-03-30 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 4,
            'created_at' => '2021-03-21 11:00:00',
            'updated_at' => now(),
            'employee_id' => 2,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 4,
            'vacation_start' => '2021-03-26 11:00:00',
            'vacation_end' => '2021-04-05 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 2,
            'created_at' => '2021-03-26 11:00:00',
            'updated_at' => now(),
            'employee_id' => 3,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 5,
            'vacation_start' => '2021-04-12 12:00:00',
            'vacation_end' => '2021-04-26 12:00:00',
            'vacation_note' => '',
            'vacation_state' => 4,
            'created_at' => '2021-04-12 12:00:00',
            'updated_at' => now(),
            'employee_id' => 7,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 6,
            'vacation_start' => '2021-04-25 14:00:00',
            'vacation_end' => '2021-05-02 14:00:00',
            'vacation_note' => '',
            'vacation_state' => 1,
            'created_at' => '2021-04-25 14:00:00',
            'updated_at' => now(),
            'employee_id' => 6,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 7,
            'vacation_start' => '2021-05-10 14:00:00',
            'vacation_end' => '2021-05-18 14:00:00',
            'vacation_note' => '',
            'vacation_state' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 5,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 8,
            'vacation_start' => '2021-05-25 14:00:00',
            'vacation_end' => '2021-06-03 14:00:00',
            'vacation_note' => '',
            'vacation_state' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 4,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 9,
            'vacation_start' => '2021-06-14 14:00:00',
            'vacation_end' => '2021-06-21 14:00:00',
            'vacation_note' => '',
            'vacation_state' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 3,
        ]);
    }
}
