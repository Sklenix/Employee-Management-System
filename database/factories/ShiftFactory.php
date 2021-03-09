<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shift::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shift_start' => $this->faker->dateTimeBetween('-2 week', '-1 week'),
            'shift_end' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'shift_note' => $this->faker->realText(50),
            'shift_place' => $this->faker->city,
            'shift_importance_id' => $this->faker->numberBetween(1,5),
            'company_id' => 1,

        ];
    }
}
