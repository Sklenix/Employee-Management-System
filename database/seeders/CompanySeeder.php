<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder {
    /* Nazev souboru: CompanySeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_companies definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_companies')->insert([
            'company_name' => 'Testovací firma',
            'company_user_name' => 'Pavel',
            'company_user_surname' => 'Sklenář',
            'email' => 'test@gmail.com',
            'company_phone' => '123456789',
            'company_login' => 'testovaci',
            'company_email_verified_at' => now(),
            'password' => Hash::make('qwertz1234'),
            'company_ico' => '12345678',
            'company_city' => 'Brno',
            'company_street' => 'Třebíčská 10'
        ]);
    }
}

