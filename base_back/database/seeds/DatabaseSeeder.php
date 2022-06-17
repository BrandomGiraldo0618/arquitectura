<?php

use App\Models\Partido;
use App\Models\Persona;
use App\Models\Tipo;
use Database\Seeders\CandidatoSeeder;
use Database\Seeders\MesaSeeder;
use Database\Seeders\PartidoSeeder;
use Database\Seeders\PersonaSeeder;
use Database\Seeders\PuntoVotacioSeeder;
use Database\Seeders\VotanteSeeder;
use Database\Seeders\VotoSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $nomTipos = ['Cámara','Senado'];

        foreach ($nomTipos as $nomTipo) {
            $tipo = new Tipo;
            $tipo->nombre = $nomTipo;
            $tipo->save();
        }

        $this->call([
           RolesAndPermissionsSeeder::class,
           UserSeeder::class,
           PersonaSeeder::class,
           PartidoSeeder::class,
           PuntoVotacioSeeder::class,
           MesaSeeder::class,
           VotanteSeeder::class,
           CandidatoSeeder::class,
           VotoSeeder::class,
        ]);

        $persona = new Persona;
        $persona->tipo_documento = 'COD_PAIS';
        $persona->numero_documento = '57';
        $persona->nombre = 'República';
        $persona->apellido = 'Colombiana';
        $persona->lugar_nacimiento = 'Colombia';
        $persona->fecha_nacimiento = '20/07/1810';
        $persona->save();

        $partido = new Partido;
        $partido->nombre = 'Voto en Blanco';
        $partido->personaid_rlegal = $persona->id;
        $partido->listaa_c = false;
        $partido->save();

    }
}
