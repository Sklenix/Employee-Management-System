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
            'employee_name' => $this->faker->firstName,
            'employee_surname' => $this->faker->lastName,
            'employee_phone' => $this->faker->phoneNumber,
            'employee_note' => $this->faker->randomAscii,
            'employee_position' => $this->faker->randomAscii,
            'employee_city' => $this->faker->city,
            'employee_street' => $this->faker->streetAddress,
            'employee_company' => '1',
            'email' => $this->faker->email,
            'employee_login' => $this->faker->userName,
            'password' => Hash::make('maly1234'),
            'remember_token' => Str::random(10),
        ];
    }
}

