<?php

namespace Database\Factories;

use App\Models\Candidato;
use App\Models\Partido;
use App\Models\Tipo;
use App\Models\Votante;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'votante_id' => Votante::all()->random()->id,
            'tipo_id' => Tipo::all()->random()->id,
            'partido_id' => Partido::all()->random()->id,
            'candidato_id' => Candidato::all()->random()->id,
            'fecha' => now(),
        ];
    }
}
