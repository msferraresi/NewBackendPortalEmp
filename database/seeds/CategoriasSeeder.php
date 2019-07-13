<?php

use Illuminate\Database\Seeder;
use arsatapi\Categoria;
class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create([
            'id' => 1,
            'name' => 'Datanet'
        ]);

        Categoria::create([
            'id' => 2,
            'name' => 'Categoria de prueba 1'
        ]);

        Categoria::create([
            'id' => 3,
            'name' => 'Categoria de prueba 2'
        ]);
    }
}
