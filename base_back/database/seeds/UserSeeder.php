<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        $user->name = 'Ingenieria informatica';
        $user->email = 'ingenieria@gmail.com';
        $user->password = '$2y$10$NTslMzsTR5ztapbFc.s0Q.piG1A1yM8g2NBcqndYR.pEC.fViqXGy'; // 123456
        $user->remember_token = Str::random(10);
        $user->cellphone = '321';
        $user->address = 'Cra 6 #6-66';
        $user->last_date_connection = date("Y-m-d H:i:s");
        $user->role_id = 1;
        $user->save();

        $user->assignRole('Admin');
    }
}
