<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('table_employees')->insert([
            'employee_name' => 'Martin',
            'employee_surname' => 'Holý',
            'employee_email' => 'holy@gmail.com',
            'employee_login' => 'holy12',
            'employee_password' => Hash::make('lolec1234'),
            'remember_token' => Str::random(10),
        ]);
    }
}
