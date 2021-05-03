<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory {
    /* Nazev souboru: AdminFactory.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato trida slouzi jako tovarna pro vyrobu zaznamu admina */

    /* Napojeni na model */
    protected $model = Admin::class;

    /* Definice samotneho zaznamu */
    public function definition(){
        return [
            'admin_name' => 'Admin',
            'admin_surname' => 'Admin',
            'admin_email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'admin_login' => 'admin'
        ];
    }
}
