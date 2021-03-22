<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('table_reports')->insert([
            'report_id' => 1,
            'report_title' => 'Tiskárna',
            'report_description' => 'Doplnit papír',
            'report_state' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 5,
            'report_importance_id' => 3
        ]);

        DB::table('table_reports')->insert([
            'report_id' => 2,
            'report_title' => 'ESET',
            'report_description' => 'Aktualizovat na nejnovější verzi',
            'report_state' => 4,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 1,
            'report_importance_id' => 2
        ]);
    }
}
