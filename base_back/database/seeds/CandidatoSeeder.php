<?php

namespace Database\Seeders;

use App\Models\Candidato;
use App\Models\Persona;
use Illuminate\Database\Seeder;

class CandidatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Candidato::factory()
            ->count(50)
            ->create();
    }
}
