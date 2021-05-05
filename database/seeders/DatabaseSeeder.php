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
            'reason_description' => 'Nemocný',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_description' => 'Nepřišel',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_description' => 'Odmítl',
        ]);
        DB::table('table_absence_reasons')->insert([
            'reason_description' => 'Zpoždění',
        ]);
        DB::table('table_absence_reasons')->insert([
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
            'created_at' => '2021-02-09 10:00:00',
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
            'created_at' => '2021-02-18 12:00:00',
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
            'created_at' => '2021-02-25 10:00:00',
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
            'created_at' => '2021-03-05 10:00:00',
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
            'created_at' => '2021-03-15 14:00:00',
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
            'created_at' => '2021-03-25 11:00:00',
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
            'created_at' => '2021-04-18 09:00:00',
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
            'created_at' => '2021-05-02 15:00:00',
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
            'created_at' => '2021-03-20 12:00:00',
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
            'created_at' => '2021-02-10 11:00:00',
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
            'created_at' => '2021-02-05 11:00:00',
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
            'created_at' => '2021-03-06 15:00:00',
            'updated_at' => now(),
            'employee_id' => 1
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 5,
            'disease_name' => 'Horečka',
            'disease_from' => '2021-04-06 15:00:00',
            'disease_to' => '2021-04-14 15:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => '2021-04-06 15:00:00',
            'updated_at' => now(),
            'employee_id' => 3
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 6,
            'disease_name' => 'Nevolnost',
            'disease_from' => '2021-04-08 15:00:00',
            'disease_to' => '2021-04-18 15:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => '2021-04-08 15:00:00',
            'updated_at' => now(),
            'employee_id' => 4
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 7,
            'disease_name' => 'Angína',
            'disease_from' => '2021-04-12 14:00:00',
            'disease_to' => '2021-04-25 14:00:00',
            'disease_state' => 3,
            'disease_note' => '',
            'created_at' => '2021-04-12 14:00:00',
            'updated_at' => now(),
            'employee_id' => 2
        ]);

        DB::table('table_diseases')->insert([
            'disease_id' => 8,
            'disease_name' => 'Nevolnost',
            'disease_from' => '2021-05-12 14:00:00',
            'disease_to' => '2021-05-24 14:00:00',
            'disease_state' => 2,
            'disease_note' => '',
            'created_at' => '2021-05-12 14:00:00',
            'updated_at' => now(),
            'employee_id' => 3
        ]);

        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Fatální',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Důležité',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Normální',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Zaučení',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Nedůležité',
        ]);
        DB::table('table_importances_shifts')->insert([
            'importance_description' => 'Nespecifikováno',
        ]);

        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Zásadní',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Naléhavé',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Důležité',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Normální',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Nedůležité',
        ]);
        DB::table('table_reports_importances')->insert([
            'importance_report_description' => 'Nespecifikováno',
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '1',
            'shift_start' => '2021-03-09 10:00:00',
            'shift_end' => '2021-03-09 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-03-09 10:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '2',
            'shift_start' => '2021-03-12 10:00:00',
            'shift_end' => '2021-03-12 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-03-12 10:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '3',
            'shift_start' => '2021-03-13 10:00:00',
            'shift_end' => '2021-03-13 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-13 10:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '4',
            'shift_start' => '2021-02-12 08:00:00',
            'shift_end' => '2021-02-12 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-02-12 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '5',
            'shift_start' => '2021-02-06 08:00:00',
            'shift_end' => '2021-02-06 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-02-06 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '6',
            'shift_start' => '2021-01-05 08:00:00',
            'shift_end' => '2021-01-05 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-01-05 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '7',
            'shift_start' => '2021-01-08 08:00:00',
            'shift_end' => '2021-01-08 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-01-08 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '8',
            'shift_start' => '2021-01-09 08:00:00',
            'shift_end' => '2021-01-09 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '6',
            'company_id' => '1',
            'created_at' => '2021-01-09 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '9',
            'shift_start' => '2021-03-20 08:00:00',
            'shift_end' => '2021-03-20 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => '2021-03-20 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '10',
            'shift_start' => '2021-03-18 08:00:00',
            'shift_end' => '2021-03-18 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => '2021-03-18 08:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '11',
            'shift_start' => '2021-03-15 09:00:00',
            'shift_end' => '2021-03-15 17:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => '2021-03-15 09:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '12',
            'shift_start' => '2021-03-14 11:00:00',
            'shift_end' => '2021-03-14 19:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-03-14 11:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '13',
            'shift_start' => '2021-03-22 11:00:00',
            'shift_end' => '2021-03-22 19:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-03-22 11:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '14',
            'shift_start' => '2021-03-23 11:00:00',
            'shift_end' => '2021-03-23 19:00:00',
            'shift_place' => 'Praha',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-03-23 11:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '15',
            'shift_start' => '2021-03-24 10:00:00',
            'shift_end' => '2021-03-24 18:00:00',
            'shift_place' => 'Praha',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-03-24 10:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '16',
            'shift_start' => '2021-03-25 09:00:00',
            'shift_end' => '2021-03-25 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-25 09:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '17',
            'shift_start' => '2021-03-26 13:00:00',
            'shift_end' => '2021-03-26 18:00:00',
            'shift_place' => 'Třebíč',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-26 13:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '18',
            'shift_start' => '2021-03-27 09:00:00',
            'shift_end' => '2021-03-27 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-27 09:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '19',
            'shift_start' => '2021-03-28 09:00:00',
            'shift_end' => '2021-03-28 18:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-28 09:00:00',
            'updated_at' => now(),
        ]);
        DB::table('table_shifts')->insert([
            'shift_id' => '20',
            'shift_start' => '2021-03-29 09:00:00',
            'shift_end' => '2021-03-29 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-03-29 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '21',
            'shift_start' => '2021-04-01 08:00:00',
            'shift_end' => '2021-04-01 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-04-01 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '22',
            'shift_start' => '2021-04-02 09:00:00',
            'shift_end' => '2021-04-02 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-04-02 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '23',
            'shift_start' => '2021-04-04 09:00:00',
            'shift_end' => '2021-04-04 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-04-04 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '24',
            'shift_start' => '2021-04-12 09:00:00',
            'shift_end' => '2021-04-12 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '5',
            'company_id' => '1',
            'created_at' => '2021-04-12 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '25',
            'shift_start' => '2021-04-14 09:00:00',
            'shift_end' => '2021-04-14 18:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-04-14 09:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '26',
            'shift_start' => '2021-04-15 08:00:00',
            'shift_end' => '2021-04-15 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-04-15 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '27',
            'shift_start' => '2021-04-18 08:00:00',
            'shift_end' => '2021-04-18 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-04-18 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '28',
            'shift_start' => '2021-04-20 08:00:00',
            'shift_end' => '2021-04-20 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-04-20 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '29',
            'shift_start' => '2021-04-25 08:00:00',
            'shift_end' => '2021-04-25 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '1',
            'company_id' => '1',
            'created_at' => '2021-04-25 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '30',
            'shift_start' => '2021-04-26 08:00:00',
            'shift_end' => '2021-04-26 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-04-26 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '31',
            'shift_start' => '2021-04-28 08:00:00',
            'shift_end' => '2021-04-28 16:00:00',
            'shift_place' => 'Jihlava',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-04-28 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '32',
            'shift_start' => '2021-05-01 08:00:00',
            'shift_end' => '2021-05-01 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-05-01 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '33',
            'shift_start' => '2021-05-02 08:00:00',
            'shift_end' => '2021-05-02 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => '2021-05-02 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '34',
            'shift_start' => '2021-05-03 08:00:00',
            'shift_end' => '2021-05-03 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => '2021-05-03 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '35',
            'shift_start' => '2021-05-04 08:00:00',
            'shift_end' => '2021-05-04 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => '2021-05-04 08:00:00',
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '36',
            'shift_start' => '2021-05-12 08:00:00',
            'shift_end' => '2021-05-12 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '37',
            'shift_start' => '2021-05-13 08:00:00',
            'shift_end' => '2021-05-13 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '38',
            'shift_start' => '2021-05-14 08:00:00',
            'shift_end' => '2021-05-14 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '39',
            'shift_start' => '2021-05-15 08:00:00',
            'shift_end' => '2021-05-15 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '1',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '40',
            'shift_start' => '2021-05-16 08:00:00',
            'shift_end' => '2021-05-16 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '41',
            'shift_start' => '2021-05-25 08:00:00',
            'shift_end' => '2021-05-25 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '2',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '42',
            'shift_start' => '2021-05-26 08:00:00',
            'shift_end' => '2021-05-26 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '43',
            'shift_start' => '2021-05-27 08:00:00',
            'shift_end' => '2021-05-27 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '44',
            'shift_start' => '2021-05-28 08:00:00',
            'shift_end' => '2021-05-28 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '45',
            'shift_start' => '2021-05-29 08:00:00',
            'shift_end' => '2021-05-29 16:00:00',
            'shift_place' => 'Ostrava',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '46',
            'shift_start' => '2021-05-30 08:00:00',
            'shift_end' => '2021-05-30 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '47',
            'shift_start' => '2021-06-01 08:00:00',
            'shift_end' => '2021-06-01 16:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '48',
            'shift_start' => '2021-06-02 09:00:00',
            'shift_end' => '2021-06-02 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '49',
            'shift_start' => '2021-06-03 09:00:00',
            'shift_end' => '2021-06-03 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '50',
            'shift_start' => '2021-06-04 09:00:00',
            'shift_end' => '2021-06-04 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '4',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '51',
            'shift_start' => '2021-06-05 09:00:00',
            'shift_end' => '2021-06-05 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_shifts')->insert([
            'shift_id' => '52',
            'shift_start' => '2021-06-06 09:00:00',
            'shift_end' => '2021-06-06 18:00:00',
            'shift_place' => 'Brno',
            'shift_importance_id' => '3',
            'company_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('table_reports')->insert([
            'report_id' => 1,
            'report_title' => 'Tiskárna',
            'report_description' => 'Doplnit papír.',
            'report_state' => 2,
            'created_at' => '2021-02-05 11:00:00',
            'updated_at' => now(),
            'employee_id' => 5,
            'report_importance_id' => 3
        ]);
        DB::table('table_reports')->insert([
            'report_id' => 2,
            'report_title' => 'ESET',
            'report_description' => 'Aktualizovat na nejnovější verzi.',
            'report_state' => 4,
            'created_at' => '2021-03-05 11:00:00',
            'updated_at' => now(),
            'employee_id' => 1,
            'report_importance_id' => 2
        ]);

        DB::table('table_reports')->insert([
            'report_id' => 3,
            'report_title' => 'Windows',
            'report_description' => 'Aktualizovat na nejnovější verzi.',
            'report_state' => 2,
            'created_at' => '2021-03-06 15:00:00',
            'updated_at' => now(),
            'employee_id' => 4,
            'report_importance_id' => 2
        ]);

        DB::table('table_reports')->insert([
            'report_id' => 4,
            'report_title' => 'Myčka',
            'report_description' => 'Přestala fungovat.',
            'report_state' => 2,
            'created_at' => '2021-03-09 13:00:00',
            'updated_at' => now(),
            'employee_id' => 3,
            'report_importance_id' => 1
        ]);

        DB::table('table_reports')->insert([
            'report_id' => 5,
            'report_title' => 'Frézka',
            'report_description' => 'Přestala fungovat.',
            'report_state' => 2,
            'created_at' => '2021-04-10 12:00:00',
            'updated_at' => now(),
            'employee_id' => 2,
            'report_importance_id' => 1
        ]);

        DB::table('table_vacations')->insert([
            'vacation_id' => 1,
            'vacation_start' => '2021-02-04 11:00:00',
            'vacation_end' => '2021-02-21 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 2,
            'created_at' => '2021-02-04 11:00:00',
            'updated_at' => now(),
            'employee_id' => 5,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 2,
            'vacation_start' => '2021-03-05 11:00:00',
            'vacation_end' => '2021-03-15 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 3,
            'created_at' => '2021-03-05 11:00:00',
            'updated_at' => now(),
            'employee_id' => 1,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 3,
            'vacation_start' => '2021-03-21 11:00:00',
            'vacation_end' => '2021-03-30 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 4,
            'created_at' => '2021-03-21 11:00:00',
            'updated_at' => now(),
            'employee_id' => 2,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 4,
            'vacation_start' => '2021-03-26 11:00:00',
            'vacation_end' => '2021-04-05 11:00:00',
            'vacation_note' => '',
            'vacation_state' => 2,
            'created_at' => '2021-03-26 11:00:00',
            'updated_at' => now(),
            'employee_id' => 3,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 5,
            'vacation_start' => '2021-04-12 12:00:00',
            'vacation_end' => '2021-04-26 12:00:00',
            'vacation_note' => '',
            'vacation_state' => 4,
            'created_at' => '2021-04-12 12:00:00',
            'updated_at' => now(),
            'employee_id' => 7,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 6,
            'vacation_start' => '2021-04-25 14:00:00',
            'vacation_end' => '2021-05-02 14:00:00',
            'vacation_note' => '',
            'vacation_state' => 1,
            'created_at' => '2021-04-25 14:00:00',
            'updated_at' => now(),
            'employee_id' => 6,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 7,
            'vacation_start' => '2021-05-10 14:00:00',
            'vacation_end' => '2021-05-18 14:00:00',
            'vacation_note' => '',
            'vacation_state' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 5,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 8,
            'vacation_start' => '2021-05-25 14:00:00',
            'vacation_end' => '2021-06-03 14:00:00',
            'vacation_note' => '',
            'vacation_state' => 3,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 4,
        ]);
        DB::table('table_vacations')->insert([
            'vacation_id' => 9,
            'vacation_start' => '2021-06-14 14:00:00',
            'vacation_end' => '2021-06-21 14:00:00',
            'vacation_note' => '',
            'vacation_state' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'employee_id' => 3,
        ]);
    }
}
