<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubCategoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('Categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60)->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        
        Schema::create('subCategorias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60)->unique();
            $table->unsignedInteger('id_categoria');
            $table->foreign('id_categoria')->references('id')->on('Categorias');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Categorias');
        Schema::dropIfExists('subCategorias');
    }
}
