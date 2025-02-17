<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LanguageSeeder extends Seeder {
    /* Nazev souboru: LanguageSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_company_languages definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_company_languages')->insert([
            'language_id' => '1',
            'language_name' => 'Čeština',
            'company_id' => '1',
        ]);
        DB::table('table_company_languages')->insert([
            'language_id' => '2',
            'language_name' => 'Angličtina',
            'company_id' => '1',
        ]);
        DB::table('table_company_languages')->insert([
            'language_id' => '3',
            'language_name' => 'Španělština',
            'company_id' => '1',
        ]);
        DB::table('table_company_languages')->insert([
            'language_id' => '4',
            'language_name' => 'Italština',
            'company_id' => '1',
        ]);
        DB::table('table_company_languages')->insert([
            'language_id' => '5',
            'language_name' => 'Ruština',
            'company_id' => '1',
        ]);
        DB::table('table_company_languages')->insert([
            'language_id' => '6',
            'language_name' => 'Čínština',
            'company_id' => '1',
        ]);
        DB::table('table_company_languages')->insert([
            'language_id' => '7',
            'language_name' => 'Slovenština',
            'company_id' => '1',
        ]);
        DB::table('table_company_languages')->insert([
            'language_id' => '8',
            'language_name' => 'Němčina',
            'company_id' => '1',
        ]);
    }
}
