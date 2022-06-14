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
            'tipo_Documento'=>$documentKind[$this->faker->numberBetween(0,1)],
            'numero_Documento'=>$this->faker->numberBetween(1000,999999999),
            'nombre'=>$this->faker->name(),
            'apellido'=>$this->faker->lastName(),
            'lugar_Nacimiento'=>$this->faker->city,
            'fecha_Nacimiento'=>$this->faker->date("Y-m-d", mt_rand(0, 500000000))
        ];
    }
}
