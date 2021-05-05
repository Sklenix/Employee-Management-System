<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiseasesSeeder extends Seeder {
    /* Nazev souboru: DiseasesSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_diseases definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_diseases')->insert([
            'disease_id' => 1,
            'disease_name' => 'Angína',
            'disease_from' => '2021-03-20 12:00:00',
            'disease_to' => '2021-03-28 12:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => '2021-03-20 12:00:00',
            'updated_at' => now(),
            'employee_id' => 3
        ]);
        DB::table('table_diseases')->insert([
            'disease_id' => 2,
            'disease_name' => 'Nevolnost',
            'disease_from' => '2021-02-10 11:00:00',
            'disease_to' => '2021-02-15 11:00:00',
            'disease_state' => 3,
            'disease_note' => '',
            'created_at' => '2021-02-10 11:00:00',
            'updated_at' => now(),
            'employee_id' => 4
        ]);
        DB::table('table_diseases')->insert([
            'disease_id' => 3,
            'disease_name' => 'Bolest hlavy',
            'disease_from' => '2021-02-05 11:00:00',
            'disease_to' => '2021-02-15 11:00:00',
            'disease_state' => 4,
            'disease_note' => '',
            'created_at' => '2021-02-05 11:00:00',
            'updated_at' => now(),
            'employee_id' => 5
        ]);
        DB::table('table_diseases')->insert([
            'disease_id' => 4,
            'disease_name' => 'Kašel',
            'disease_from' => '2021-03-06 15:00:00',
            'disease_to' => '2021-03-12 15:00:00',
            'disease_state' => 1,
            'disease_note' => '',
            'created_at' => '2021-03-06 15:00:00',
            'updated_at' => now(),
            'employee_id' => 1
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 5,
            'disease_name' => 'Horečka',
            'disease_from' => '2021-04-06 15:00:00',
            'disease_to' => '2021-04-14 15:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => '2021-04-06 15:00:00',
            'updated_at' => now(),
            'employee_id' => 3
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 6,
            'disease_name' => 'Nevolnost',
            'disease_from' => '2021-04-08 15:00:00',
            'disease_to' => '2021-04-18 15:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => '2021-04-08 15:00:00',
            'updated_at' => now(),
            'employee_id' => 4
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 7,
            'disease_name' => 'Angína',
            'disease_from' => '2021-04-12 14:00:00',
            'disease_to' => '2021-04-25 14:00:00',
            'disease_state' => 3,
            'disease_note' => '',
            'created_at' => '2021-04-12 14:00:00',
            'updated_at' => now(),
            'employee_id' => 2
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 8,
            'disease_name' => 'Nevolnost',
            'disease_from' => '2021-05-12 14:00:00',
            'disease_to' => '2021-05-24 14:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => '2021-05-12 14:00:00',
            'updated_at' => now(),
            'employee_id' => 3
        ]);
    }
}
