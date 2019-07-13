<?php

use Illuminate\Database\Seeder;
use arsatapi\subcategoria;
use Illuminate\Support\Facades\DB;
class subcategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        subcategoria::create([
            'id' => 1,
            'name' => 'Reproceso',
            'id_categoria'=> 1
        ]);

        subcategoria::create([
            'id' => 2,
            'name' => 'Subcategoria de prueba 1',
            'id_categoria'=> 2
        ]);

        subcategoria::create([
            'id' => 3,
            'name' => 'Subcategoria de prueba 2',
            'id_categoria'=> 2
        ]);

        subcategoria::create([
            'id' => 4,
            'name' => 'Subcategoria de prueba 3',
            'id_categoria'=> 3
        ]);

        subcategoria::create([
            'id' => 5,
            'name' => 'Subcategoria de prueba 4',
            'id_categoria'=> 1
        ]);

    }
}
