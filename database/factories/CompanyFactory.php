<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyFactory extends Factory {
    /* Nazev souboru: CompanyFactory.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi jako tovarna pro vyrobu zaznamu firmy */

    /* Napojeni na model */
    protected $model = Company::class;

    /* Definice samotneho zaznamu */
    public function definition(){
        return [
            'company_name' => 'Testovací firma',
            'company_user_name' => 'Pavel',
            'company_user_surname' => 'Sklenář',
            'email' => 'Fload158@gmail.com',
            'company_phone' => '123456789',
            'company_login' => 'testovaci',
            'email_verified_at' => now(),
            'company_url' => '1S96Y2IjnxcpuEq2TrENVIgs64jOOgN3H',
            'password' => Hash::make('qwertz1234'),
            'company_ico' => '12345678',
            'company_city' => 'Brno',
            'company_street' =>'Třebíčská 10'
        ];
    }
}

