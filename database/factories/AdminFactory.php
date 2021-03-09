<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'admin_name' => 'Admin',
            'admin_surname' => 'Admin',
            'admin_email' => 'admin@gmail.com',
            'admin_password' => Hash::make('admin'),
            'admin_login' => 'admin',
            'remember_token' => Str::random(10),
        ];
    }
}
