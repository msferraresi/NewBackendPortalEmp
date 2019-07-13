<?php

use Illuminate\Database\Seeder;
use arsatapi\permisos_x_subcategorias;

class permisos_x_subcategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        permisos_x_subcategorias::create([
            'id' => 1,
            'id_per' => 1,
            'id_sub' => 1
        ]);

        permisos_x_subcategorias::create([
            'id' => 2,
            'id_per' => 2,
            'id_sub' => 1
        ]);

        permisos_x_subcategorias::create([
            'id' => 3,
            'id_per' => 3,
            'id_sub' => 1
        ]);

        permisos_x_subcategorias::create([
            'id' => 4,
            'id_per' => 4,
            'id_sub' => 1
        ]);

        permisos_x_subcategorias::create([
            'id' => 5,
            'id_per' => 5,
            'id_sub' => 1
        ]);
    }
}
