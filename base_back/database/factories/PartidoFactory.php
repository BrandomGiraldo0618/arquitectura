<?php

namespace Database\Factories;

use App\Models\Tipo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre'=>$this->faker->name(),
            'personaid_rlegal' => $this->faker->numberBetween(1,10),
            'listaa_c'=>$this->faker->boolean(),
            'tipo_id'=> Tipo::all()->random()->id,
        ];
    }
}
