<?php

namespace Database\Seeders;

use App\Models\Persona;
use Database\Factories\PersonaFactory;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        Persona::factory()
                ->count(160)
                ->create();
    }
}
