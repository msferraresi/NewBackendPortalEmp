<?php

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
        $this->call(RolesAndPermissions::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriasSeeder::class);
        $this->call(subcategoriaSeeder::class);
        $this->call(permisos_x_subcategoriasSeeder::class);
        $this->call(TypeFilesSeeder::class);
    }
}
