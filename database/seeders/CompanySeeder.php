<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('table_companies')->insert([
            'company_name' => 'Google',
            'company_user_name' => 'Josef',
            'company_user_surname' => 'Malý',
            'email' => 'google@gmail.com',
            'company_phone' => '123456789',
            'company_login' => 'sklenixa',
            'company_email_verified_at' => now(),
            'password' => Hash::make('ahoj1234'),
            'company_ico' => '12345678',
            'company_city' => 'Velké Meziříčí',
            'company_street' =>'Třebíčská 10',
            'remember_token' => Str::random(10)
        ]);
    }
}

