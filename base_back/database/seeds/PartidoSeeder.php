<?php

namespace Database\Seeders;

use App\Models\Partido;
use Illuminate\Database\Seeder;

class PartidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partido::factory()
                ->count(10)
                ->create();
    }
}
