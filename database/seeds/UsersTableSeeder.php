<?php

use Illuminate\Database\Seeder;
//use TSA\User;
use arsatapi\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::create([
            'name' => 'Admin',
            'lastname'=> 'General',
            'email' => 'administrador@admin.app.arsat',
            'employeeid' => 0,
        ]);
        $admin->assignRole('super-admin');
    }
}
