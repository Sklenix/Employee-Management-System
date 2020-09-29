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
            'company_first_name' => 'Josef',
            'company_surname' => 'Malý',
            'company_email' => 'google@gmail.com',
            'company_phone' => '123456789',
            'company_login' => 'sklenix',
            'company_email_verified_at' => now(),
            'company_password' => Hash::make('ahoj1234'),
            'remember_token' => Str::random(10),
        ]);
    }
}
