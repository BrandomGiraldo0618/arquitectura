<?php

namespace Database\Factories;

use App\Models\Partido;
use App\Models\Persona;
use App\Models\Tipo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {      
        return [
            'persona_id'  => Persona::all()->random()->id,
            'numero_inscripcion' => $this->faker->numberBetween(1, 50),
            'partido_id' => Partido::all()->random()->id,
            'tipo_id' => Tipo::all()->random()->id,
        ];
    }
}
