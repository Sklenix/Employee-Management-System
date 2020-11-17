<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_name' => 'Sklenářství',
            'company_user_name' => 'Pavel',
            'company_user_surname' => 'Sklenář',
            'email' => 'Fload158@gmail.com',
            'company_phone' => '123456789',
            'company_login' => 'sklenix',
            'email_verified_at' => now(),
            'company_url' => '1FsQa0y8jHnCNkZw9g9SSECRkfgvuUbWy',
            'password' => Hash::make('ahoj1234'),
            'remember_token' => Str::random(10),
            'company_ico' => '12345678',
            'company_city' => 'Velké Meziříčí',
            'company_street' =>'Třebíčská 10'
        ];
    }
}

