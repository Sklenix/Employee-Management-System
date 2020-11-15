<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_name' => 'Jan',
            'employee_surname' => 'Malý',
            'employee_phone' => '123456789',
            'employee_note' => 'pracovitý',
            'employee_position' => 'skladník',
            'employee_city' => 'Brno',
            'employee_street' => 'Jihlavská 10',
            'employee_company' => '1',
            'email' => 'fuurin555@gmail.com',
            'employee_login' => 'maly123',
            'password' => Hash::make('maly1234'),
            'remember_token' => Str::random(10),
        ];
    }
}

