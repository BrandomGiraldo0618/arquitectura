<?php

namespace Database\Factories;

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
            'persona_id'=>$this->faker->rand(1,5),
            'mesa_id'=>$this->faker->rand(1,5)
        ];
    }
}
