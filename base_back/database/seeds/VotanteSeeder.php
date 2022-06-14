<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\Votante;
use Illuminate\Database\Seeder;

class VotanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Votante::factory()
                ->count(100)
                ->create();
    }
}
