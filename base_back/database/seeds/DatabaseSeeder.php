<?php
use App\Models\PuntoVotacio;
use Illuminate\Database\Seeder;
use Database\Seeders\PuntoVotacioSeeder;
use Database\Factories\PuntoVotacionFactory;





class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
           RolesAndPermissionsSeeder::class,
           UserSeeder::class,
           //Mesa::class,
           //Votante::class,
        ]);

        //PuntoVotacioSeeder::class;
       //PuntoVotacio::factory(5)->create();
       //Mesa::factory(5)->create();
       //Votante::factory(5)->create();
       //Persona::factory(5)->create();
    }
}
