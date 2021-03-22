<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiseasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('table_diseases')->insert([
            'disease_id' => 1,
            'disease_name' => 'Angína',
            'disease_from' => '2021-03-20 12:00:00',
            'disease_to' => '2021-03-28 12:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => now(),
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
            'created_at' => now(),
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
            'created_at' => now(),
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
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 1
        ]);
    }
}
