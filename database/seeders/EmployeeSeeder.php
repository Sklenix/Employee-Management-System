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
            'employee_name' => 'George',
            'employee_surname' => 'Butler',
            'employee_phone' => '123456789',
            'email' => 'butler@gmail.com',
            'employee_position' => 'Skladník',
            'employee_city' => 'Jihlava',
            'employee_street' => 'Třebíčská 10',
            'employee_reliability' => 3,
            'employee_absence' => 2,
            'employee_workindex' => 1,
            'employee_overall' => 2,
            'employee_login' => 'butler',
            'password' => Hash::make('qwertz1234'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'employee_company' => '1',
        ]);

        DB::table('table_employees')->insert([
            'employee_name' => 'Josh',
            'employee_surname' => 'Pearl',
            'employee_phone' => '159159159',
            'email' => 'pearl@gmail.com',
            'employee_position' => 'Mechanik',
            'employee_city' => 'Brno',
            'employee_street' => 'Semilasso',
            'employee_reliability' => 4,
            'employee_absence' => 4,
            'employee_workindex' => 4,
            'employee_overall' => 4,
            'employee_login' => 'pearl',
            'password' => Hash::make('qwertz1234'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'employee_company' => '1',
        ]);

        DB::table('table_employees')->insert([
            'employee_name' => 'Albert',
            'employee_surname' => 'Xander',
            'employee_phone' => '987654321',
            'email' => 'xander@gmail.com',
            'employee_position' => 'HR asistent',
            'employee_city' => 'Brno',
            'employee_street' => 'Jihlavská 12',
            'employee_reliability' => 3,
            'employee_absence' => 4,
            'employee_workindex' => 5,
            'employee_overall' => 4,
            'employee_login' => 'xander',
            'password' => Hash::make('qwertz1234'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'employee_company' => '1',
        ]);

        DB::table('table_employees')->insert([
            'employee_name' => 'Benjamin',
            'employee_surname' => 'Felix',
            'employee_phone' => '147147147',
            'email' => 'felix@gmail.com',
            'employee_position' => 'Mechanik',
            'employee_city' => 'Jihlava',
            'employee_street' => 'Markova 2',
            'employee_reliability' => 1,
            'employee_absence' => 5,
            'employee_workindex' => 3,
            'employee_overall' => 3,
            'employee_login' => 'felix',
            'password' => Hash::make('qwertz1234'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'employee_company' => '1',
        ]);

        DB::table('table_employees')->insert([
            'employee_name' => 'Andre',
            'employee_surname' => 'Bush',
            'employee_phone' => '258258258',
            'email' => 'bush@gmail.com',
            'employee_position' => 'HR asistent',
            'employee_city' => 'Brno',
            'employee_street' => 'Technická 10',
            'employee_reliability' => 4,
            'employee_absence' => 3,
            'employee_workindex' => 5,
            'employee_overall' => 4,
            'employee_login' => 'bush',
            'password' => Hash::make('qwertz1234'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'employee_company' => '1',
        ]);

        DB::table('table_employees')->insert([
            'employee_name' => 'Esme',
            'employee_surname' => 'Maxwell',
            'employee_phone' => '123123123',
            'email' => 'maxwell@gmail.com',
            'employee_position' => 'Vedoucí',
            'employee_city' => 'Brno',
            'employee_street' => 'Technická 12',
            'employee_reliability' => 2,
            'employee_absence' => 2,
            'employee_workindex' => 2,
            'employee_overall' => 2,
            'employee_login' => 'maxwell',
            'password' => Hash::make('qwertz1234'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'employee_company' => '1',
        ]);

        DB::table('table_employees')->insert([
            'employee_name' => 'Erik',
            'employee_surname' => 'Laurent',
            'employee_phone' => '157157157',
            'email' => 'laurent@gmail.com',
            'employee_position' => 'Manažer',
            'employee_city' => 'Třebíč',
            'employee_street' => 'Hlavní 8',
            'employee_reliability' => 3,
            'employee_absence' => 3,
            'employee_workindex' => 3,
            'employee_overall' => 3,
            'employee_login' => 'laurent',
            'password' => Hash::make('qwertz1234'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'employee_company' => '1',
        ]);

        DB::table('table_employees')->insert([
            'employee_name' => 'Fergus',
            'employee_surname' => 'Bale',
            'employee_phone' => '252252252',
            'email' => 'bale@gmail.com',
            'employee_position' => 'Správce IT',
            'employee_city' => 'Jihlava',
            'employee_street' => 'Průmyslová 10',
            'employee_reliability' => 4,
            'employee_absence' => 1,
            'employee_workindex' => 1,
            'employee_overall' => 2,
            'employee_login' => 'bale',
            'password' => Hash::make('qwertz1234'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'employee_company' => '1',
        ]);
    }
}
