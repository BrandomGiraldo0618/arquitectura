<?php

namespace Database\Seeders;

use App\Models\PuntoVotacio;
use Illuminate\Database\Seeder;

class PuntoVotacioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PuntoVotacio::factory()
                ->count(100)
                ->create();
    }
}
