<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder {
    /* Tato trida slouzi pro naplneni cele databaze hodnotami definovanymi v metode run. Po napsani prikazu php artisan db:seed se automaticky aktivuje tento seeder
       Vice informaci o seederech: https://laravel.com/docs/8.x/seeding. Tento seeder je soucasti frameworku Laravel. Metoda run byla upravena pro vkladani zaznamu do informacniho systemu pro spravu zamestnancu
       ve firme */
    public function run(){
        Company::factory(1)->create();
        Admin::factory(1)->create();

        DB::table('table_absence_reasons')->insert([
            'reason_value' => '1',
            'reason_description' => 'Nemocný',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '2',
            'reason_description' => 'Nepřišel',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '3',
            'reason_description' => 'Odmítl',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '4',
            'reason_description' => 'Zpoždění',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_value' => '5',
            'reason_description' => 'OK',
        ]);

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

        DB::table('table_employees')->insert([
            'employee_name' => 'George',
            'employee_surname' => 'Butler',
            'employee_phone' => '123456789',
            'email' => 'butler@gmail.com',
            'employee_position' => 'Skladník',
            'employee_city' => 'Jihlava',
            'employee_birthday' => '1970-05-05',
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
            'employee_birthday' => '1968-04-05',
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
            'employee_birthday' => '1985-03-12',
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
            'employee_birthday' => '1992-06-18',
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
            'employee_birthday' => '1986-12-24',
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
            'employee_birthday' => '1996-01-05',
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
            'employee_birthday' => '1990-07-08',
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
            'employee_birthday' => '1999-06-14',
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

        DB::table('table_diseases')->insert([
            'disease_id' => 1,
            'disease_name' => 'Angína',
            'disease_from' => '2021-03-20 12:00:00',
            'disease_to' => '2021-03-28 12:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 3
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 2,
            'disease_name' => 'Nevolnost',
            'disease_from' => '2021-02-10 11:00:00',
            'disease_to' => '2021-02-15 11:00:00',
            'disease_state' => 3,
            'disease_note' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 4
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 3,
            'disease_name' => 'Bolest hlavy',
            'disease_from' => '2021-02-05 11:00:00',
            'disease_to' => '2021-02-15 11:00:00',
            'disease_state' => 4,
            'disease_note' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 5
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 4,
            'disease_name' => 'Kašel',
            'disease_from' => '2021-03-06 15:00:00',
            'disease_to' => '2021-03-12 15:00:00',
            'disease_state' => 1,
            'disease_note' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 1
        ]);

        DB::table('table_importances_shifts')->insert([
            'importance_value' => '1',
            'importance_description' => 'Fatální',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '2',
            'importance_description' => 'Důležité',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '3',
            'importance_description' => 'Normální',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '4',
            'importance_description' => 'Zaučení',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '5',
            'importance_description' => 'Nedůležité',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_value' => '6',
            'importance_description' => 'Nespecifikováno',
        ]);

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

        DB::table('table_shifts')->insert([
            'shift_id' => '1',
            'shift_start' => '2021-03-09 10:00:00',
            'shift_end' => '2021-03-09 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '2',
            'shift_start' => '2021-03-12 10:00:00',
            'shift_end' => '2021-03-12 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '3',
            'shift_start' => '2021-03-13 10:00:00',
            'shift_end' => '2021-03-13 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '4',
            'shift_start' => '2021-02-12 08:00:00',
            'shift_end' => '2021-02-12 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '5',
            'shift_start' => '2021-02-06 08:00:00',
            'shift_end' => '2021-02-06 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '6',
            'shift_start' => '2021-01-05 08:00:00',
            'shift_end' => '2021-01-05 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '7',
            'shift_start' => '2021-01-08 08:00:00',
            'shift_end' => '2021-01-08 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '8',
            'shift_start' => '2021-01-09 08:00:00',
            'shift_end' => '2021-01-09 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '6',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '9',
            'shift_start' => '2021-03-20 08:00:00',
            'shift_end' => '2021-03-20 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '10',
            'shift_start' => '2021-03-18 08:00:00',
            'shift_end' => '2021-03-18 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '11',
            'shift_start' => '2021-03-15 09:00:00',
            'shift_end' => '2021-03-15 17:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '12',
            'shift_start' => '2021-03-14 11:00:00',
            'shift_end' => '2021-03-14 19:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '13',
            'shift_start' => '2021-03-22 11:00:00',
            'shift_end' => '2021-03-22 19:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '14',
            'shift_start' => '2021-03-23 11:00:00',
            'shift_end' => '2021-03-23 19:00:00',
            'shift_place' => 'Praha',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '15',
            'shift_start' => '2021-03-24 10:00:00',
            'shift_end' => '2021-03-24 18:00:00',
            'shift_place' => 'Praha',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '16',
            'shift_start' => '2021-03-25 09:00:00',
            'shift_end' => '2021-03-25 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '17',
            'shift_start' => '2021-03-26 13:00:00',
            'shift_end' => '2021-03-26 18:00:00',
            'shift_place' => 'Třebíč',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '18',
            'shift_start' => '2021-03-27 09:00:00',
            'shift_end' => '2021-03-27 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '19',
            'shift_start' => '2021-03-28 09:00:00',
            'shift_end' => '2021-03-28 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '20',
            'shift_start' => '2021-03-29 09:00:00',
            'shift_end' => '2021-03-29 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        DB::table('table_vacations')->insert([
            'vacation_id' => 1,
            'vacation_start' => '2021-02-04 11:00:00',
            'vacation_end' => '2021-02-21 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 5,
        ]);

        DB::table('table_vacations')->insert([
            'vacation_id' => 2,
            'vacation_start' => '2021-03-05 11:00:00',
            'vacation_end' => '2021-03-15 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 1,
        ]);

        DB::table('table_vacations')->insert([
            'vacation_id' => 3,
            'vacation_start' => '2021-03-21 11:00:00',
            'vacation_end' => '2021-03-30 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 4,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 2,
        ]);

        DB::table('table_vacations')->insert([
            'vacation_id' => 4,
            'vacation_start' => '2021-03-26 11:00:00',
            'vacation_end' => '2021-04-05 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 3,
        ]);

    }
}
