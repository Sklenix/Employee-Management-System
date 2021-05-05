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
            'created_at' => '2021-03-09 10:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '2',
            'shift_start' => '2021-03-12 10:00:00',
            'shift_end' => '2021-03-12 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-03-12 10:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '3',
            'shift_start' => '2021-03-13 10:00:00',
            'shift_end' => '2021-03-13 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-13 10:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '4',
            'shift_start' => '2021-02-12 08:00:00',
            'shift_end' => '2021-02-12 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-02-12 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '5',
            'shift_start' => '2021-02-06 08:00:00',
            'shift_end' => '2021-02-06 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-02-06 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '6',
            'shift_start' => '2021-01-05 08:00:00',
            'shift_end' => '2021-01-05 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-01-05 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '7',
            'shift_start' => '2021-01-08 08:00:00',
            'shift_end' => '2021-01-08 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-01-08 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '8',
            'shift_start' => '2021-01-09 08:00:00',
            'shift_end' => '2021-01-09 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '6',
            'company_id' => '1',
            'created_at' => '2021-01-09 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '9',
            'shift_start' => '2021-03-20 08:00:00',
            'shift_end' => '2021-03-20 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => '2021-03-20 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '10',
            'shift_start' => '2021-03-18 08:00:00',
            'shift_end' => '2021-03-18 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => '2021-03-18 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '11',
            'shift_start' => '2021-03-15 09:00:00',
            'shift_end' => '2021-03-15 17:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => '2021-03-15 09:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '12',
            'shift_start' => '2021-03-14 11:00:00',
            'shift_end' => '2021-03-14 19:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-03-14 11:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '13',
            'shift_start' => '2021-03-22 11:00:00',
            'shift_end' => '2021-03-22 19:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-03-22 11:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '14',
            'shift_start' => '2021-03-23 11:00:00',
            'shift_end' => '2021-03-23 19:00:00',
            'shift_place' => 'Praha',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-03-23 11:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '15',
            'shift_start' => '2021-03-24 10:00:00',
            'shift_end' => '2021-03-24 18:00:00',
            'shift_place' => 'Praha',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-03-24 10:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '16',
            'shift_start' => '2021-03-25 09:00:00',
            'shift_end' => '2021-03-25 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-25 09:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '17',
            'shift_start' => '2021-03-26 13:00:00',
            'shift_end' => '2021-03-26 18:00:00',
            'shift_place' => 'Třebíč',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-26 13:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '18',
            'shift_start' => '2021-03-27 09:00:00',
            'shift_end' => '2021-03-27 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-27 09:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '19',
            'shift_start' => '2021-03-28 09:00:00',
            'shift_end' => '2021-03-28 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-28 09:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '20',
            'shift_start' => '2021-03-29 09:00:00',
            'shift_end' => '2021-03-29 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-29 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '21',
            'shift_start' => '2021-04-01 08:00:00',
            'shift_end' => '2021-04-01 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-04-01 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '22',
            'shift_start' => '2021-04-02 09:00:00',
            'shift_end' => '2021-04-02 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-04-02 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '23',
            'shift_start' => '2021-04-04 09:00:00',
            'shift_end' => '2021-04-04 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-04-04 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '24',
            'shift_start' => '2021-04-12 09:00:00',
            'shift_end' => '2021-04-12 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => '2021-04-12 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '25',
            'shift_start' => '2021-04-14 09:00:00',
            'shift_end' => '2021-04-14 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-04-14 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '26',
            'shift_start' => '2021-04-15 08:00:00',
            'shift_end' => '2021-04-15 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-04-15 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '27',
            'shift_start' => '2021-04-18 08:00:00',
            'shift_end' => '2021-04-18 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-04-18 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '28',
            'shift_start' => '2021-04-20 08:00:00',
            'shift_end' => '2021-04-20 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-04-20 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '29',
            'shift_start' => '2021-04-25 08:00:00',
            'shift_end' => '2021-04-25 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '1',
            'company_id' => '1',
            'created_at' => '2021-04-25 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '30',
            'shift_start' => '2021-04-26 08:00:00',
            'shift_end' => '2021-04-26 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-04-26 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '31',
            'shift_start' => '2021-04-28 08:00:00',
            'shift_end' => '2021-04-28 16:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-04-28 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '32',
            'shift_start' => '2021-05-01 08:00:00',
            'shift_end' => '2021-05-01 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-05-01 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '33',
            'shift_start' => '2021-05-02 08:00:00',
            'shift_end' => '2021-05-02 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-05-02 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '34',
            'shift_start' => '2021-05-03 08:00:00',
            'shift_end' => '2021-05-03 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-05-03 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '35',
            'shift_start' => '2021-05-04 08:00:00',
            'shift_end' => '2021-05-04 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-05-04 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '36',
            'shift_start' => '2021-05-12 08:00:00',
            'shift_end' => '2021-05-12 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '37',
            'shift_start' => '2021-05-13 08:00:00',
            'shift_end' => '2021-05-13 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '38',
            'shift_start' => '2021-05-14 08:00:00',
            'shift_end' => '2021-05-14 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '39',
            'shift_start' => '2021-05-15 08:00:00',
            'shift_end' => '2021-05-15 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '1',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '40',
            'shift_start' => '2021-05-16 08:00:00',
            'shift_end' => '2021-05-16 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '41',
            'shift_start' => '2021-05-25 08:00:00',
            'shift_end' => '2021-05-25 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '42',
            'shift_start' => '2021-05-26 08:00:00',
            'shift_end' => '2021-05-26 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '43',
            'shift_start' => '2021-05-27 08:00:00',
            'shift_end' => '2021-05-27 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '44',
            'shift_start' => '2021-05-28 08:00:00',
            'shift_end' => '2021-05-28 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '45',
            'shift_start' => '2021-05-29 08:00:00',
            'shift_end' => '2021-05-29 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '46',
            'shift_start' => '2021-05-30 08:00:00',
            'shift_end' => '2021-05-30 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '47',
            'shift_start' => '2021-06-01 08:00:00',
            'shift_end' => '2021-06-01 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '48',
            'shift_start' => '2021-06-02 09:00:00',
            'shift_end' => '2021-06-02 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '49',
            'shift_start' => '2021-06-03 09:00:00',
            'shift_end' => '2021-06-03 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '50',
            'shift_start' => '2021-06-04 09:00:00',
            'shift_end' => '2021-06-04 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '51',
            'shift_start' => '2021-06-05 09:00:00',
            'shift_end' => '2021-06-05 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '52',
            'shift_start' => '2021-06-06 09:00:00',
            'shift_end' => '2021-06-06 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
