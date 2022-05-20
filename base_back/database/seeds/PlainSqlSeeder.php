<?php

namespace Database\Seeders;

use DB;
use Eloquent;
use Illuminate\Database\Seeder;

class PlainSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->command->info('Plain sql seeder');

        //Insert data in departments table
        $path = database_path('custom/departments.sql');
        DB::unprepared(file_get_contents($path));

        //Insert data in cities table
        $path = database_path('custom/cities.sql');
        DB::unprepared(file_get_contents($path));

         //Create v_users view
         $path = database_path('views/v_users.sql');
         DB::unprepared(file_get_contents($path));
    }
}
