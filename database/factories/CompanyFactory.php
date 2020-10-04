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
            'company_name' => 'Google',
            'company_first_name' => 'Josef',
            'company_surname' => 'Malý',
            'company_email' => 'Fload158@gmail.com',
            'company_phone' => '123456789',
            'company_login' => 'sklenix',
            'company_email_verified_at' => now(),
            'company_password' => Hash::make('ahoj1234'),
            'remember_token' => Str::random(10),
        ];
    }
}
