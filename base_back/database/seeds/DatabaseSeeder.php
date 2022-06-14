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
        $persona->tipo_Documento = 'COD_PAIS';
        $persona->numero_Documento = '57';
        $persona->nombre = 'República';
        $persona->apellido = 'Colombiana';
        $persona->lugar_Nacimiento = 'Colombia';
        $persona->fecha_Nacimiento = '20/07/1810';
        $persona->save();

        $partido = new Partido;
        $partido->nombre = 'Voto en Blanco';
        $partido->personaId_Rlegal = $persona->id;
        $partido->listaA_C = false;
        $partido->save();

    }
}
