<?php

namespace Database\Factories;

use App\Models\PuntoVotacio;
use Illuminate\Database\Eloquent\Factories\Factory;

class MesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre'=> "Mesa ". $this->faker->numberBetween(1,100),
            'punto_votacio_id'=> PuntoVotacio::all()->random()->id,
        ];
    }
}
