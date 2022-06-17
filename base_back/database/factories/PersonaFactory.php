<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $documentKind = ['CC','TI'];
        return [
            'tipo_documento'=>$documentKind[$this->faker->numberBetween(0,1)],
            'numero_documento'=>$this->faker->numberBetween(1000,999999999),
            'nombre'=>$this->faker->name(),
            'apellido'=>$this->faker->lastName(),
            'lugar_nacimiento'=>$this->faker->city,
            'fecha_nacimiento'=>$this->faker->date("Y-m-d", mt_rand(0, 500000000))
        ];
    }
}
