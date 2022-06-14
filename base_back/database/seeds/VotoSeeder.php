<?php

namespace Database\Seeders;

use App\Models\Voto;
use Illuminate\Database\Seeder;

class VotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Voto::factory()
                ->count(100)
                ->create();
    }
}
