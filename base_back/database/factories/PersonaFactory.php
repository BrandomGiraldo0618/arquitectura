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
        return [
            'tipo_documento'=>$this->faker->text,
            'numero_Documento'=>$this->faker->rand(1000,5000),
            'nombre'=>$this->faker->text,
            'apellido'=>$this->faker->text,
            'lugar_Nacimiento'=>$this->faker->text,
            'fecha_Nacimiento'=>$this->faker->date("Y-m-d", mt_rand(0, 500000000))

        ];
    }
}
