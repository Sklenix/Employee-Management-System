<?php

namespace Database\Factories;

use App\Models\ImportancesShifts;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportancesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImportancesShifts::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'importance_value' => $this->faker->randomNumber(),
            'importance_description' => $this->faker->randomAscii,
        ];
    }
}
