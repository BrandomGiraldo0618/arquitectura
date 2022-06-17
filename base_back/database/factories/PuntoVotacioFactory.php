<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PuntoVotacioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre'=>$this->faker->text(10),
            'direccion'=>$this->faker->text(150)
        ];
    }
}
