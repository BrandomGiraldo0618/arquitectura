<?php

namespace Database\Factories;

use App\Models\Mesa;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotanteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'huella'=>$this->faker->text,
            'persona_id'=> Persona::all()->random()->id,
            'mesa_id'=> Mesa::all()->random()->id
        ];
    }
}
