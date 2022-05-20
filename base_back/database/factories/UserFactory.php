<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy', // 123456
            'status' => 1,
            'remember_token' => Str::random(10),
            'role_id' => $this->faker->numberBetween($min = 1, $max = 2)
        ];
    }
}
