<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
    /* Nazev souboru: AdminSeeder.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi pro naplneni tabulky table_admins definovanymi hodnotami uvedenymi v metode run.
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding */
    public function run(){
        DB::table('table_admins')->insert([
            'admin_name' => 'Admin',
            'admin_surname' => 'Admin',
            'admin_email' => 'admin@gmail.com',
            'admin_login' => 'admin',
            'password' => Hash::make('qwertz1234')
        ]);
    }
}
