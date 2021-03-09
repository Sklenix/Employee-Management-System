<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportancesReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('table_reports_importances')->insert([
            'importance_report_value' => '1',
            'importance_report_description' => 'Zásadní',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_value' => '2',
            'importance_report_description' => 'Naléhavé',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_value' => '3',
            'importance_report_description' => 'Důležité',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_value' => '4',
            'importance_report_description' => 'Normální',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_value' => '5',
            'importance_report_description' => 'Nedůležité',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_value' => '6',
            'importance_report_description' => 'Nespecifikováno',
        ]);

    }
}
