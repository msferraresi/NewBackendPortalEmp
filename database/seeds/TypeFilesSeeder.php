<?php

use Illuminate\Database\Seeder;
use arsatapi\TypeFile;
class TypeFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeFile::create([
            'description' => 'Recibo de Sueldo',
        ]);
        TypeFile::create([
            'description' => 'Horas Extras',
        ]);
        TypeFile::create([
            'description' => 'SAC',
        ]);
        TypeFile::create([
            'description' => 'Vacaciones',
        ]);
    }
}
