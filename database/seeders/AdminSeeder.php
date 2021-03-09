<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('table_admin')->insert([
            'admin_name' => 'Josef',
            'admin_surname' => 'Malý',
            'admin_email' => 'google@gmail.com',
            'admin_login' => 'admin',
            'admin_password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
        ]);
    }
}
